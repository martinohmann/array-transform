<?php declare(strict_types=1);
/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
