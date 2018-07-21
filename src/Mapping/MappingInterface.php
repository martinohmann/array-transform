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

use ArrayTransform\Rule\RuleInterface;

interface MappingInterface
{
    /**
     * @return RuleInterface[]
     */
    public function getRules(): array;

    /**
     * @return RuleInterface[]
     */
    public function getReverseRules(): array;

    /**
     * @return string
     */
    public function getKeySeparator(): string;
}
