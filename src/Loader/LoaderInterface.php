<?php declare(strict_types=1);

namespace ArrayTransform\Loader;

use ArrayTransform\Exception\TransformException;

interface LoaderInterface
{
    /**
     * @param string $fileName
     * @return array
     */
    public function load(string $fileName): array;
}
