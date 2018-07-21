<?php declare(strict_types=1);
/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayTransform\Rule;

use ArrayTransform\Exception\MappingException;

interface RuleFactoryInterface
{
    /**
     * @param string $directKey
     * @param array $config
     * @return RuleInterface
     * @throws MappingException
     */
    public function createRule(string $directKey, array $config): RuleInterface;
}
