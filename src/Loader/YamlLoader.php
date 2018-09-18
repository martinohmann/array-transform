<?php declare(strict_types=1);
/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayTransform\Loader;

use ArrayTransform\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

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
