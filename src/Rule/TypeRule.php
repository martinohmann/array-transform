<?php declare(strict_types=1);

namespace ArrayTransform\Rule;

class TypeRule implements RuleInterface
{
    /**
     * @var RuleInterface
     */
    private $rule;

    /**
     * @var string
     */
    private $type;

    /**
     * @param RuleInterface $rule
     * @param string $type
     */
    public function __construct(RuleInterface $rule, string $type)
    {
        $this->rule = $rule;
        $this->type = $type;
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
     * @param mixed $value
     * @return mixed
     */
    private function castValue($value)
    {
        settype($value, $this->type);

        return $value;
    }
}
