<?php declare(strict_types=1);

namespace ArrayTransform\Rule;

use ArrayTransform\Exception\NotReversibleException;

interface RuleInterface
{
    /**
     * @param array $data
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
     * @return RuleInterface
     * @throws NotReversibleException
     */
    public function reverse(): RuleInterface;
}
