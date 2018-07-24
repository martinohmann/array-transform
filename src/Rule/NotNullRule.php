<?php declare(strict_types=1);

namespace ArrayTransform\Rule;

use ArrayTransform\Exception\NotNullableException;

class NotNullRule implements RuleInterface
{
    /**
     * @var RuleInterface
     */
    private $rule;
    /**
     * @var bool
     */
    private $sourceNotNull;
    /**
     * @var bool
     */
    private $targetNotNull;

    /**
     * @param RuleInterface $rule
     * @param bool $sourceNotNull
     * @param bool $targetNotNull
     */
    public function __construct(RuleInterface $rule, bool $sourceNotNull, bool $targetNotNull)
    {
        $this->rule = $rule;
        $this->sourceNotNull = $sourceNotNull;
        $this->targetNotNull = $targetNotNull;
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

        if ($this->targetNotNull && null === $value) {
            throw new NotNullableException(
                \sprintf(
                    'found null value, but target key "%s" is not nullable',
                    $this->rule->getTargetKey()
                )
            );
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverse(): RuleInterface
    {
        return new static($this->rule->reverse(), $this->targetNotNull, $this->sourceNotNull);
    }
}
