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

use PHPUnit\Framework\TestCase;
use Phake;
use ArrayTransform\Rule\SimpleFormulaRule;
use ArrayTransform\Rule\RuleInterface;
use ArrayTransform\Rule\SimpleRule;

class SimpleFormulaRuleTest extends TestCase
{
    /**
     * @test
     */
    public function itImplementsTheRuleInterface()
    {
        $rule = new SimpleFormulaRule(Phake::mock(RuleInterface::class), 'foo * 100', 'bar / 100');

        $this->assertInstanceOf(RuleInterface::class, $rule);
    }

    /**
     * @test
     */
    public function itIsReversible()
    {
        $ruleMock = Phake::mock(RuleInterface::class);
        $data = [
            'foo' => 1,
        ];

        Phake::when($ruleMock)->reverse()->thenReturn($ruleMock);

        $rule = new SimpleFormulaRule($ruleMock, 'foo * 10', 'bar / 10');
        $reversed = $rule->reverse();

        $this->assertSame(10, $reversed->resolveValue($data));
    }

    /**
     * @test
     */
    public function itMatchesKeysOfTheWrappedRule()
    {
        $rule = new SimpleFormulaRule(new SimpleRule('foo', 'bar'), null, null);

        $this->assertSame('foo', $rule->getSourceKey());
        $this->assertSame('bar', $rule->getTargetKey());
    }

    /**
     * @test
     * @dataProvider getTestData
     */
    public function itEvaluatesFormulas(array $data, ?string $targetFormula, $expected)
    {
        $rule = new SimpleFormulaRule(Phake::mock(RuleInterface::class), null, $targetFormula);

        $this->assertSame($expected, $rule->resolveValue($data));
    }

    public function getTestData(): array
    {
        return [
            'simple formula' => [
                [
                    'foo' => 1,
                ],
                'foo * 10',
                10,
            ],
            'multiple variables formula' => [
                [
                    'foo' => 2,
                    'bar' => 1.5,
                    'baz' => 10,
                ],
                'foo * bar * baz',
                30.0,
            ],
            'formula with parenthesis' => [
                [
                    'foo' => 2,
                    'bar' => 1.5,
                    'baz' => 10,
                ],
                '(foo - bar) * baz',
                5.0,
            ],
            'null formula' => [
                [],
                null,
                null,
            ],
            'unresolved variables' => [
                [],
                'somekey * 10',
                null,
            ],
            'malformed formula' => [
                [
                    'foo' => 1
                ],
                'foo */ 10',
                null,
            ],
        ];
    }
}
