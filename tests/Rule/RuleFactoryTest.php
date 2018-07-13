<?php

namespace ArrayTransform\Tests\Rule;

use PHPUnit\Framework\TestCase;
use ArrayTransform\Rule\RuleFactory;
use ArrayTransform\Exception\MappingException;
use ArrayTransform\Rule\TypeRule;
use ArrayTransform\Key\KeyParser;

class RuleFactoryTest extends TestCase
{
    /**
     * @var RuleFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new RuleFactory(new KeyParser());
    }

    /**
     * @test
     */
    public function itCreatesSimpleRuleFromConfig()
    {
        $config = [
            'sourceKey' => 'foo',
            'targetKey' => 'bar',
        ];

        $rule = $this->factory->createRule($config);

        $this->assertSame($config['sourceKey'], $rule->getSourceKey());
        $this->assertSame($config['targetKey'], $rule->getTargetKey());
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfConfigIsIncomplete()
    {
        $this->expectException(MappingException::class);
        $this->factory->createRule([]);
    }

    /**
     * @test
     */
    public function itCreatesTypeRuleFromConfig()
    {
        $config = [
            'sourceKey' => 'foo[string]',
            'targetKey' => 'bar[int]',
        ];

        $rule = $this->factory->createRule($config);

        $this->assertInstanceOf(TypeRule::class, $rule);
    }
}
