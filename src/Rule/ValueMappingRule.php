<?php declare(strict_types=1);

namespace ArrayTransform\Rule;

use ArrayTransform\Rule\RuleInterface;

class ValueMappingRule implements RuleInterface
{
    /**
     * @var RuleInterface
     */
    private $rule;

    /**
     * @var array
     */
    private $sourceValues = [];

    /**
     * @var array
     */
    private $targetValues = [];

    /**
     * @var mixed
     */
    private $defaultStrategy;

    /**
     * @param RuleInterface $rule
     * @param mixed $defaultStrategy
     */
    public function __construct(RuleInterface $rule, $defaultStrategy = null)
    {
        $this->rule = $rule;
        $this->defaultStrategy = $defaultStrategy;
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
    public function resolveValue(array $data)
    {
        $value = $this->rule->resolveValue($data);

        return $this->mapValue($value);
    }

    /**
     * {@inheritdoc}
     */
    public function reverse(): RuleInterface
    {
        $reverseRule = new static($this->rule->reverse(), $this->defaultStrategy);

        foreach ($this->sourceValues as $i => $sourceValue) {
            $reverseRule->addValueMapping($this->targetValues[$i], $sourceValue);
        }

        return $reverseRule;
    }

    /**
     * @param mixed $sourceValue
     * @param mixed $targetValue
     * @return void
     */
    public function addValueMapping($sourceValue, $targetValue)
    {
        $this->sourceValues[] = $sourceValue;
        $this->targetValues[] = $targetValue;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function mapValue($value)
    {
        $pos = \array_search($value, $this->sourceValues);

        if (false !== $pos) {
            return $this->targetValues[$pos];
        } elseif ($this->defaultStrategy == 'pass_through') {
            return $value;
        } elseif (\is_callable($this->defaultStrategy)) {
            return \call_user_func($this->defaultStrategy, $value);
        }

        return null;
    }
}
