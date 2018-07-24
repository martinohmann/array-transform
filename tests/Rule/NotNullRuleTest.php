<?php

namespace ArrayTransform\Tests\Rule;

use PHPUnit\Framework\TestCase;
use \Phake;
use ArrayTransform\Rule\RuleInterface;
use ArrayTransform\Rule\NotNullRule;
use ArrayTransform\Exception\NotNullableException;
use ArrayTransform\Rule\SimpleRule;

class NotNullRuleTest extends TestCase
{
    /**
     * @test
     */
    public function itImplementsRuleInterface()
    {
        $rule = new NotNullRule(Phake::mock(RuleInterface::class), true, false);

        $this->assertInstanceOf(RuleInterface::class, $rule);
    }

    /**
     * @test
     * @dataProvider providerTestData
     */
    public function itHandlesNullableFields($givenValue, bool $targetNotNull, bool $expectException)
    {
        $ruleMock = Phake::mock(RuleInterface::class);

        Phake::when($ruleMock)->resolveValue->thenReturn($givenValue);

        $rule = new NotNullRule($ruleMock, true, $targetNotNull);

        if ($expectException) {
            $this->expectException(NotNullableException::class);
            $rule->resolveValue([]);
        } else {
            $this->assertSame($givenValue, $rule->resolveValue([]));
        }
    }

    /**
     * @return array
     */
    public function providerTestData(): array
    {
        return [
            'nullable: pass null' => [null, false, false],
            'nullable: pass non-null value' => ['somevalue', false, false],
            'not nullable: throw exception on null' => [null, true, true],
            'not nullable: pass non-null value' => ['somevalue', true, false],
            'not nullable: pass empty value' => ['', true, false],
        ];
    }

    /**
     * @test
     */
    public function isIsReversible()
    {
        $ruleMock = Phake::mock(RuleInterface::class);
        $data = [];

        Phake::when($ruleMock)->reverse()->thenReturn($ruleMock);
        Phake::when($ruleMock)->resolveValue($data)->thenReturn(null);

        $rule = new NotNullRule($ruleMock, true, false);
        $reversed = $rule->reverse();

        $this->expectException(NotNullableException::class);
        $reversed->resolveValue($data);
    }

    /**
     * @test
     */
    public function itMatchesKeysOfTheWrappedRule()
    {
        $rule = new NotNullRule(new SimpleRule('foo', 'bar'), true, true);

        $this->assertSame('foo', $rule->getSourceKey());
        $this->assertSame('bar', $rule->getTargetKey());
    }
}
