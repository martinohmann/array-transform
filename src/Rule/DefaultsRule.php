<?php declare(strict_types=1);

namespace ArrayTransform\Rule;

class DefaultsRule implements RuleInterface
{
    /**
     * @var RuleInterface
     */
    private $rule;

    /**
     * @var mixed
     */
    private $sourceDefault;

    /**
     * @var mixed
     */
    private $targetDefault;

    /**
     * @param RuleInterface $rule
     * @param mixed $sourceDefault
     * @param mixed $targetDefault
     */
    public function __construct(RuleInterface $rule, $sourceDefault, $targetDefault)
    {
        $this->rule = $rule;
        $this->sourceDefault = $sourceDefault;
        $this->targetDefault = $targetDefault;
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
        return $data[$this->rule->getSourceKey()] ?? $this->sourceDefault;
    }

    /**
     * {@inheritdoc}
     */
    public function reverse(): RuleInterface
    {
        return new static($this->rule->reverse(), $this->targetDefault, $this->sourceDefault);
    }
}
