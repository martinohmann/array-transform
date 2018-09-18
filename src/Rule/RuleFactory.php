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

use ArrayTransform\Exception\MappingException;
use ArrayTransform\Key\KeyParser;
use ArrayTransform\Key\TypedKey;

class RuleFactory implements RuleFactoryInterface
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
     * @throws MappingException
     * @return RuleInterface
     */
    public function createRule(string $directKey, array $config): RuleInterface
    {
        if (empty($directKey) && (!isset($config['inverse']) || empty($config['inverse']))) {
            throw new MappingException('direct and inverse key cannot be both empty at the same time.');
        }

        $targetKey = $this->keyParser->parseKey($directKey);
        $sourceKey = $this->keyParser->parseKey($config['inverse'] ?? '');

        $rule = $this->createSimpleRule($sourceKey, $targetKey);

        if (isset($config['defaults']['direct']) || isset($config['defaults']['inverse'])) {
            $rule = $this->createDefaultsRule($rule, $config['defaults']);
        }

        if (isset($config['formula']['direct']) || isset($config['formula']['inverse'])) {
            $rule = $this->createFormulaRule($rule, $config['formula']);
        } elseif (isset($config['value_mapping']['mapping'])) {
            $rule = $this->createValueMappingRule($rule, $config['value_mapping']);
        }

        if ($sourceKey->hasType() || $targetKey->hasType()) {
            $rule = $this->createTypeRule($rule, $sourceKey, $targetKey);
        }

        if (isset($config['not_null']['direct']) || isset($config['not_null']['inverse'])) {
            $rule = $this->createNotNullRule($rule, $config['not_null']);
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
    private function createDefaultsRule(RuleInterface $rule, array $config): RuleInterface
    {
        return new DefaultsRule($rule, $config['inverse'] ?? null, $config['direct'] ?? null);
    }

    /**
     * @param RuleInterface $rule
     * @param array $config
     * @return RuleInterface
     */
    private function createFormulaRule(RuleInterface $rule, array $config): RuleInterface
    {
        return new SimpleFormulaRule($rule, $config['inverse'] ?? '', $config['direct'] ?? '');
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
            if (!\array_key_exists('direct', $values) || !\array_key_exists('inverse', $values)) {
                throw new MappingException('direct and inverse key of value mapping must not be empty');
            }

            $rule->addValueMapping($values['inverse'], $values['direct']);
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

    /**
     * @param RuleInterface $rule
     * @param array $config
     * @return RuleInterface
     */
    private function createNotNullRule(RuleInterface $rule, array $config): RuleInterface
    {
        $targetNotNull = isset($config['direct']) ? (bool) $config['direct'] : false;
        $sourceNotNull = isset($config['inverse']) ? (bool) $config['inverse'] : false;

        return new NotNullRule($rule, $sourceNotNull, $targetNotNull);
    }
}
