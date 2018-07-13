<?php declare(strict_types=1);

namespace ArrayTransform\Rule;

use ArrayTransform\Exception\MappingException;

class RuleFactory
{
    /**
     * @param array $config
     * @return RuleInterface
     * @throws MappingException
     */
    public function createRule(array $config): RuleInterface
    {
        if (!isset($config['sourceKey']) || !isset($config['targetKey'])) {
            throw new MappingException('Rule config requires "sourceKey" and "targetKey" fields');
        }

        $rule = new SimpleRule($config['sourceKey'], $config['targetKey']);

        if (isset($config['types']['targetKey'])) {
            $rule = new TypeRule($rule, $config['types']['targetKey']);
        }

        return $rule;
    }
}
