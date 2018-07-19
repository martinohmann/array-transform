<?php declare(strict_types=1);

namespace ArrayTransform\Loader;

use Symfony\Component\Yaml\Yaml;
use ArrayTransform\Exception\ParseException;

class YamlLoader implements LoaderInterface
{
    /**
     * @const string
     */
    const ROOT_NODE = 'mapping';

    /**
     * {@inheritdoc}
     */
    public function load(string $fileName): array
    {
        $data = Yaml::parseFile($fileName);

        if (!isset($data[self::ROOT_NODE])) {
            throw new ParseException(
                \sprintf(
                    'root node "%s" not found',
                    self::ROOT_NODE
                )
            );
        }

        if (!\is_array($data[self::ROOT_NODE])) {
            throw new ParseException(
                \sprintf(
                    'expected root node "%s" to be of type "array", found "%s"',
                    self::ROOT_NODE,
                    gettype($data[self::ROOT_NODE])
                )
            );
        }

        return $data[self::ROOT_NODE];
    }
}
