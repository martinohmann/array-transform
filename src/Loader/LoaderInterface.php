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

interface LoaderInterface
{
    /**
     * @param string $fileName
     * @return array
     * @throws ParseException
     */
    public function load(string $fileName): array;
}
