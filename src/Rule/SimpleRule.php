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
    public function resolveValue(array $data)
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

    /**
     * {@inheritdoc}
     */
    public function reverse(): RuleInterface
    {
        return new static($this->targetKey, $this->sourceKey);
    }
}
