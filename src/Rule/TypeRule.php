<?php declare(strict_types=1);

namespace ArrayTransform\Rule;

class TypeRule implements RuleInterface
{
    /**
     * @var RuleInterface
     */
    private $rule;

    /**
     * @var string|null
     */
    private $sourceType;

    /**
     * @var string|null
     */
    private $targetType;

    /**
     * @param RuleInterface $rule
     * @param string|null $sourceType
     * @param string|null $targetType
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
            settype($value, $this->targetType);
        }

        return $value;
    }
}
