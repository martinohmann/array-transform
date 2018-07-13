<?php

namespace ArrayTransform\Tests\Rule;

use PHPUnit\Framework\TestCase;
use Phake;
use ArrayTransform\Rule\SimpleRule;
use ArrayTransform\Rule\RuleInterface;
use ArrayTransform\Rule\TypeRule;

class TypeRuleTest extends TestCase
{
    /**
     * @test
     */
    public function itImplementsTheRuleInterface()
    {
        $rule = new TypeRule(Phake::mock(RuleInterface::class), 'int');

        $this->assertInstanceOf(RuleInterface::class, $rule);
    }

    /**
     * @test
     */
    public function itCastsTypes()
    {
        $ruleMock = Phake::mock(RuleInterface::class);
        $data = [];

        Phake::when($ruleMock)->resolveValue($data)->thenReturn(1);

        $rule = new TypeRule($ruleMock, 'string');

        $this->assertSame('1', $rule->resolveValue($data));
    }

    /**
     * @test
     */
    public function itMatchesKeysOfTheWrappedRule()
    {
        $rule = new TypeRule(new SimpleRule('foo', 'bar'), 'string');

        $this->assertSame('foo', $rule->getSourceKey());
        $this->assertSame('bar', $rule->getTargetKey());
    }
}
