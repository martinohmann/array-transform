<?php

namespace ArrayTransform\Tests\Rule;

use PHPUnit\Framework\TestCase;
use ArrayTransform\Rule\RuleFactory;
use ArrayTransform\Exception\MappingException;
use ArrayTransform\Rule\TypeRule;

class RuleFactoryTest extends TestCase
{
    /**
     * @var RuleFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new RuleFactory();
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
            'sourceKey' => 'foo',
            'targetKey' => 'bar',
            'types' => [
                'sourceKey' => 'int',
                'targetKey' => 'string',
            ],
        ];

        $rule = $this->factory->createRule($config);

        $this->assertInstanceOf(TypeRule::class, $rule);
    }
}
