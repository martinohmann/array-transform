<?php declare(strict_types=1);

namespace ArrayTransform\Rule;

use ArrayTransform\Exception\NotReversibleException;

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
