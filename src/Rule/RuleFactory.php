<?php declare(strict_types=1);

namespace ArrayTransform\Rule;

use ArrayTransform\Exception\MappingException;
use ArrayTransform\Key\KeyParser;
use ArrayTransform\Key\TypedKey;

class RuleFactory
{
    /**
     * @var KeyParser
     */
    private $keyParser;

    /**
     * @param KeyParser $keyParser
     */
    public function __construct(KeyParser $keyParser = null)
    {
        $this->keyParser = $keyParser ?? new KeyParser();
    }

    /**
     * @param string $directKey
     * @param array $config
     * @return RuleInterface
     * @throws MappingException
     */
    public function createRule(string $directKey, array $config): RuleInterface
    {
        if (empty($directKey) && (!isset($config['inverse']) || empty($config['inverse']))) {
            throw new MappingException('Direct and inverse key cannot be both empty at the same time.');
        }

        $sourceKey = $this->keyParser->parseKey($directKey);
        $targetKey = $this->keyParser->parseKey($config['inverse'] ?? '');

        $rule = $this->createSimpleRule($sourceKey, $targetKey);

        if (isset($config['formula']['direct']) || isset($config['formula']['inverse'])) {
            $rule = $this->createFormulaRule($rule, $config['formula']);
        } elseif (isset($config['value_mapping']['mapping'])) {
            $rule = $this->createValueMappingRule($rule, $config['value_mapping'] ?? []);
        }

        if ($sourceKey->hasType() || $targetKey->hasType()) {
            $rule = $this->createTypeRule($rule, $sourceKey, $targetKey);
        }

        return $rule;
    }

    /**
     * @param TypedKey $sourceKey
     * @param TypedKey $targetKey
     * @return RuleInterface
     */
    private function createSimpleRule(TypedKey $sourceKey, TypedKey $targetKey): RuleInterface
    {
        return new SimpleRule($sourceKey->getName(), $targetKey->getName());
    }

    /**
     * @param RuleInterface $rule
     * @param array $config
     * @return RuleInterface
     */
    private function createFormulaRule(RuleInterface $rule, array $config): RuleInterface
    {
        return new SimpleFormulaRule($rule, $config['direct'] ?? '', $config['inverse'] ?? '');
    }

    /**
     * @param RuleInterface $rule
     * @param array $config
     * @return RuleInterface
     */
    private function createValueMappingRule(RuleInterface $rule, array $config): RuleInterface
    {
        $rule = new ValueMappingRule($rule, $config['default'] ?? null);

        foreach ($config['mapping'] as $values) {
            if (\array_key_exists('direct', $values) && \array_key_exists('inverse', $values)) {
                $rule->addValueMapping($values['direct'], $values['inverse']);
            }
        }

        return $rule;
    }

    /**
     * @param RuleInterface $rule
     * @param TypedKey $sourceKey
     * @param TypedKey $targetKey
     * @return RuleInterface
     */
    private function createTypeRule(RuleInterface $rule, TypedKey $sourceKey, TypedKey $targetKey): RuleInterface
    {
        return new TypeRule($rule, $sourceKey->getType(), $targetKey->getType());
    }
}
