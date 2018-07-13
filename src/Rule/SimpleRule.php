<?php declare(strict_types=1);

namespace ArrayTransform\Rule;

class SimpleRule implements RuleInterface
{
    /**
     * @var string
     */
    private $sourceKey;

    /**
     * @var string
     */
    private $targetKey;

    /**
     * @param string $sourceKey
     * @param string $targetKey
     */
    public function __construct(string $sourceKey, string $targetKey)
    {
        $this->sourceKey = $sourceKey;
        $this->targetKey = $targetKey;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(array $data)
    {
        return $data[$this->sourceKey] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceKey(): string
    {
        return $this->sourceKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetKey(): string
    {
        return $this->targetKey;
    }
}
