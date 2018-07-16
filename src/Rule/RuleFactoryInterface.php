<?php declare(strict_types=1);

namespace ArrayTransform\Rule;

use ArrayTransform\Exception\MappingException;

interface RuleFactoryInterface
{
    /**
     * @param string $directKey
     * @param array $config
     * @return RuleInterface
     * @throws MappingException
     */
    public function createRule(string $directKey, array $config): RuleInterface;
}
