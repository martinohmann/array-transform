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

use ArrayTransform\Rule\RuleInterface;
use ArrayTransform\Rule\SimpleRule;
use ArrayTransform\Rule\TypeRule;
use Phake;
use PHPUnit\Framework\TestCase;

class TypeRuleTest extends TestCase
{
    /**
     * @test
     */
    public function itImplementsTheRuleInterface()
    {
        $rule = new TypeRule(Phake::mock(RuleInterface::class), 'int', 'string');

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

        $rule = new TypeRule($ruleMock, 'int', 'string');

        $this->assertSame('1', $rule->resolveValue($data));
    }

    /**
     * @test
     */
    public function isIsReversible()
    {
        $ruleMock = Phake::mock(RuleInterface::class);
        $data = [];

        Phake::when($ruleMock)->reverse()->thenReturn($ruleMock);
        Phake::when($ruleMock)->resolveValue($data)->thenReturn(1);

        $rule = new TypeRule($ruleMock, 'string', 'int');
        $reversed = $rule->reverse();

        $this->assertSame('1', $reversed->resolveValue($data));
    }

    /**
     * @test
     */
    public function itMatchesKeysOfTheWrappedRule()
    {
        $rule = new TypeRule(new SimpleRule('foo', 'bar'), 'string', null);

        $this->assertSame('foo', $rule->getSourceKey());
        $this->assertSame('bar', $rule->getTargetKey());
    }
}
