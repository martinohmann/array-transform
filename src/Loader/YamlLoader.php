<?php declare(strict_types=1);

namespace ArrayTransform\Loader;

use Symfony\Component\Yaml\Yaml;
use ArrayTransform\Exception\ParseException;

class YamlLoader implements LoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(string $fileName): array
    {
        $data = Yaml::parseFile($fileName);

        if (!\is_array($data)) {
            throw new ParseException(
                \sprintf(
                    'expected "array", found "%s"',
                    gettype($data)
                )
            );
        }

        return $data;
    }
}
