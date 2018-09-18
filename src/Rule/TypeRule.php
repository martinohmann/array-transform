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

class TypeRule implements RuleInterface
{
    /**
     * @var RuleInterface
     */
    private $rule;

    /**
     * @var null|string
     */
    private $sourceType;

    /**
     * @var null|string
     */
    private $targetType;

    /**
     * @param RuleInterface $rule
     * @param null|string $sourceType
     * @param null|string $targetType
     */
    public function __construct(RuleInterface $rule, ?string $sourceType, ?string $targetType)
    {
        $this->rule = $rule;
        $this->sourceType = $sourceType;
        $this->targetType = $targetType;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveValue(array $data)
    {
        $value = $this->rule->resolveValue($data);

        return $this->castValue($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceKey(): string
    {
        return $this->rule->getSourceKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetKey(): string
    {
        return $this->rule->getTargetKey();
    }

    /**
     * {@inheritdoc}
     */
    public function reverse(): RuleInterface
    {
        return new static($this->rule->reverse(), $this->targetType, $this->sourceType);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function castValue($value)
    {
        if (!empty($this->targetType)) {
            \settype($value, $this->targetType);
        }

        return $value;
    }
}
