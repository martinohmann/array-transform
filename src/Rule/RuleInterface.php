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

use ArrayTransform\Exception\NotNullableException;
use ArrayTransform\Exception\NotReversibleException;

interface RuleInterface
{
    /**
     * @param array $data
     * @throws NotNullableException
     * @return mixed
     */
    public function resolveValue(array $data);

    /**
     * @return string
     */
    public function getSourceKey(): string;

    /**
     * @return string
     */
    public function getTargetKey(): string;

    /**
     * @throws NotReversibleException
     * @return RuleInterface
     */
    public function reverse(): RuleInterface;
}
