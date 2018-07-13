<?php declare(strict_types=1);

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
