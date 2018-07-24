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

class SimpleFormulaRule implements RuleInterface
{
    /**
     * @var RuleInterface
     */
    private $rule;

    /**
     * @var string|null
     */
    private $sourceFormula;

    /**
     * @var string|null
     */
    private $targetFormula;

    /**
     * @param RuleInterface $rule
     * @param string|null $sourceFormula
     * @param string|null $targetFormula
     */
    public function __construct(RuleInterface $rule, ?string $sourceFormula, ?string $targetFormula)
    {
        $this->rule = $rule;
        $this->sourceFormula = $sourceFormula;
        $this->targetFormula = $targetFormula;
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
        $fallback = null;

        if (empty($this->targetFormula)) {
            return null;
        }

        $formula = $this->resolveVariables($data, $this->targetFormula);

        if (\preg_match('#[^\d\/\*\-\+\s\.\)\(]+#', $formula)) {
            return null;
        }

        try {
            $result = eval(\sprintf('return (%s);', $formula));
        } catch (\ParseError $e) {
            $result = null;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function reverse(): RuleInterface
    {
        return new static($this->rule->reverse(), $this->targetFormula, $this->sourceFormula);
    }

    /**
     * @param array $data
     * @param string $formula
     * @return string
     */
    private function resolveVariables(array $data, string $formula): string
    {
        foreach ($data as $key => $value) {
            $formula = \strtr(
                $formula,
                [
                    $key => $value,
                ]
            );
        }

        return $formula;
    }
}
