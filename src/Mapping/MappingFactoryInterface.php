<?php declare(strict_types=1);
/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayTransform\Mapping;

use ArrayTransform\Exception\MappingException;
use ArrayTransform\Exception\ParseException;

interface MappingFactoryInterface
{
    /**
     * @param array $config
     * @return MappingInterface
     * @throws MappingException
     * @throws ParseException
     */
    public function createMapping(array $config): MappingInterface;

    /**
     * @param string $fileName
     * @return MappingInterface
     * @throws MappingException
     * @throws ParseException
     */
    public function createMappingFromFile(string $fileName): MappingInterface;
}
