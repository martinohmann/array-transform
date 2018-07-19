<?php declare(strict_types=1);

namespace ArrayTransform\Loader;

use Symfony\Component\Yaml\Yaml;
use ArrayTransform\Exception\ParseException;

class YamlLoader implements LoaderInterface
{
    /**
     * @var string
     */
    private $rootNode;

    /**
     * @param string $rootNode
     */
    public function __construct(string $rootNode = 'mapping')
    {
        $this->rootNode = $rootNode;
    }

    /**
     * {@inheritdoc}
     */
    public function load(string $fileName): array
    {
        $data = Yaml::parseFile($fileName);

        if (!isset($data[$this->rootNode])) {
            throw new ParseException(
                \sprintf(
                    'root node "%s" not found',
                    $this->rootNode
                )
            );
        }

        if (!\is_array($data[$this->rootNode])) {
            throw new ParseException(
                \sprintf(
                    'expected root node "%s" to be of type "array", found "%s"',
                    $this->rootNode,
                    \gettype($data[$this->rootNode])
                )
            );
        }

        return $data[$this->rootNode];
    }
}
