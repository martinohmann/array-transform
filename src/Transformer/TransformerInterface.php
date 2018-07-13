<?php declare(strict_types=1);

namespace ArrayTransform\Transformer;

interface TransformerInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function transform(array $data): array;

    /**
     * @param array $data
     * @return array
     */
    public function reverseTransform(array $data): array;
}
