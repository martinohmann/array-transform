<?php declare(strict_types=1);

namespace ArrayTransform\Loader;

use ArrayTransform\Exception\ParseException;

interface LoaderInterface
{
    /**
     * @param string $fileName
     * @return array
     * @throws ParseException
     */
    public function load(string $fileName): array;
}
