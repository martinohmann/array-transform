<?php
/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayTransform\Tests\Rule;

use ArrayTransform\Rule\DefaultsRule;
use ArrayTransform\Rule\RuleInterface;
use ArrayTransform\Rule\SimpleRule;
use Phake;
use PHPUnit\Framework\TestCase;

class DefaultsRuleTest extends TestCase
{
    /**
     * @test
     */
    public function itImplementsTheRuleInterface()
    {
        $rule = new DefaultsRule(Phake::mock(RuleInterface::class), null, null);

        $this->assertInstanceOf(RuleInterface::class, $rule);
    }

    /**
     * @test
     * @dataProvider provideDefaultsTestData
     * @param mixed $sourceDefault
     * @param mixed $targetDefault
     */
    public function itReturnsDefaultsIfSourceKeyIsNotPresent($sourceDefault, $targetDefault)
    {
        $ruleMock = Phake::mock(RuleInterface::class);
        $data = [];

        $rule = new DefaultsRule($ruleMock, $sourceDefault, $targetDefault);

        $this->assertSame($sourceDefault, $rule->resolveValue($data));
    }

    /**
     * @test
     * @dataProvider provideDefaultsTestData
     * @param mixed $sourceDefault
     * @param mixed $targetDefault
     */
    public function isIsReversible($sourceDefault, $targetDefault)
    {
        $ruleMock = Phake::mock(RuleInterface::class);
        $data = [];

        Phake::when($ruleMock)->reverse()->thenReturn($ruleMock);

        $rule = new DefaultsRule($ruleMock, $sourceDefault, $targetDefault);
        $reversed = $rule->reverse();

        $this->assertSame($targetDefault, $reversed->resolveValue($data));
    }

    /**
     * @return array
     */
    public function provideDefaultsTestData(): array
    {
        return [
            [1, 2],
            [null, true],
            ['foo', 1.337],
            [['foo' => 'bar'], (object)['bar' => 'baz']],
        ];
    }

    /**
     * @test
     */
    public function itMatchesKeysOfTheWrappedRule()
    {
        $rule = new DefaultsRule(new SimpleRule('foo', 'bar'), null, null);

        $this->assertSame('foo', $rule->getSourceKey());
        $this->assertSame('bar', $rule->getTargetKey());
    }
}
