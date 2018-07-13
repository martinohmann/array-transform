<?php declare(strict_types=1);

namespace ArrayTransform\Rule;

use ArrayTransform\Exception\MappingException;
use ArrayTransform\Key\KeyParser;

class RuleFactory
{
    /**
     * @var KeyParser
     */
    private $keyParser;

    /**
     * @param KeyParser $keyParser
     */
    public function __construct(KeyParser $keyParser)
    {
        $this->keyParser = $keyParser;
    }

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

        $sourceKey = $this->keyParser->parseKey($config['sourceKey']);
        $targetKey = $this->keyParser->parseKey($config['targetKey']);

        $rule = new SimpleRule($sourceKey->getName(), $targetKey->getName());

        if ($targetKey->hasType()) {
            $rule = new TypeRule($rule, (string) $targetKey->getType());
        }

        return $rule;
    }
}
