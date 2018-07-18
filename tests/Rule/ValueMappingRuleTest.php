<?php

namespace ArrayTransform\Tests\Rule;

use PHPUnit\Framework\TestCase;
use Phake;
use ArrayTransform\Rule\RuleInterface;
use ArrayTransform\Rule\ValueMappingRule;
use ArrayTransform\Rule\SimpleRule;

class ValueMappingRuleTest extends TestCase
{
    /**
     * @test
     */
    public function itImplementsTheRuleInterface()
    {
        $rule = new ValueMappingRule(Phake::mock(RuleInterface::class));

        $this->assertInstanceOf(RuleInterface::class, $rule);
    }

    /**
     * @test
     * @dataProvider getValueMappingTestData
     */
    public function itMapsValues($sourceValue, $targetValue)
    {
        $ruleMock = Phake::mock(RuleInterface::class);

        Phake::when($ruleMock)->resolveValue->thenReturn($sourceValue);

        $rule = new ValueMappingRule($ruleMock);
        $rule->addValueMapping($sourceValue, $targetValue);

        $this->assertSame($targetValue, $rule->resolveValue([]));
    }

    /**
     * @return array
     */
    public function getValueMappingTestData(): array
    {
        return [
            'simple number mapping' => [1, 2],
            'number to string' => [1, 'one'],
            'number to null' => [1, 2],
            'null to number' => [null, 2],
        ];
    }

    /**
     * @test
     * @dataProvider getDefaultProviderTestData
     */
    public function itHonorsProviderToObtainDefaultValue($provider, $sourceValue, $targetValue)
    {
        $ruleMock = Phake::mock(RuleInterface::class);

        Phake::when($ruleMock)->resolveValue->thenReturn($sourceValue);

        $rule = new ValueMappingRule($ruleMock, $provider);

        $this->assertSame($targetValue, $rule->resolveValue([]));
    }

    /**
     * @return array
     */
    public function getDefaultProviderTestData(): array
    {
        $defaultFunc = function ($value) {
            return 2 * (int) $value;
        };

        return [
            'pass_through' => ['pass_through', 'some string', 'some string'],
            'callable array' => [[$this, 'resolveDefaultValue'], 'bar', 'string'],
            'callable static array' => [[self::class, 'staticResolveDefaultValue'], 'foo', '(string) foo'],
            'callable closure' => [$defaultFunc, '5', 10],
            'strange provider returns null by default' => ['weird provider name', 'some string', null],
        ];
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function resolveDefaultValue($value): string
    {
        return \gettype($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function staticResolveDefaultValue($value): string
    {
        return \sprintf('(%s) %s', \gettype($value), $value);
    }

    /**
     * @test
     */
    public function itIsReversible()
    {
        $rule = new ValueMappingRule(new SimpleRule('foo', 'bar'));
        $rule->addValueMapping(13, 42);

        $this->assertSame(42, $rule->resolveValue(['foo' => 13]));

        $rule = $rule->reverse();

        $this->assertSame(13, $rule->resolveValue(['bar' => 42]));
    }

    /**
     * @test
     */
    public function itMatchesKeysOfTheWrappedRule()
    {
        $rule = new ValueMappingRule(new SimpleRule('foo', 'bar'));

        $this->assertSame('foo', $rule->getSourceKey());
        $this->assertSame('bar', $rule->getTargetKey());
    }
}
