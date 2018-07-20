<?php declare(strict_types=1);

/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayTransform\Mapping;

use ArrayTransform\Rule\RuleInterface;
use ArrayTransform\Exception\NotReversibleException;

class Mapping implements MappingInterface
{
    /**
     * @var string
     */
    private $keySeparator;

    /**
     * @var RuleInterface[]
     */
    private $rules;

    /**
     * @var RuleInterface[]
     */
    private $reverseRules;

    /**
     * @param array $rules
     * @param string $keySeparator
     */
    public function __construct(array $rules = [], string $keySeparator = '.')
    {
        $this->keySeparator = $keySeparator;

        $this->setRules($rules);
    }

    /**
     * {@inheritdoc}
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * {@inheritdoc}
     */
    public function getReverseRules(): array
    {
        if (empty($this->reverseRules)) {
            foreach ($this->rules as $rule) {
                try {
                    $this->reverseRules[] = $rule->reverse();
                } catch (NotReversibleException $e) {
                    /* ignored */
                }
            }
        }

        return $this->reverseRules;
    }

    /**
     * {@inheritdoc}
     */
    public function getKeySeparator(): string
    {
        return $this->keySeparator;
    }

    /**
     * @param string $keySeparator
     * @return void
     */
    public function setKeySeparator(string $keySeparator)
    {
        $this->keySeparator = $keySeparator;
    }

    /**
     * @param RuleInterface $rule
     * @return void
     */
    public function addRule(RuleInterface $rule)
    {
        $this->rules[] = $rule;
        $this->reverseRules = [];
    }

    /**
     * @param array $rules
     * @return void
     */
    private function setRules(array $rules)
    {
        /** @var RuleInterface $rule */
        foreach ($rules as $rule) {
            if (!$rule instanceof RuleInterface) {
                throw new \InvalidArgumentException(
                    \sprintf(
                        'expected object of type "%s", found "%s"',
                        RuleInterface::class,
                        \is_object($rule) ? \get_class($rule) : \gettype($rule)
                    )
                );
            }
        }

        $this->rules = $rules;
        $this->reverseRules = [];
    }
}
