<?php

namespace ArrayTransform\Tests\Rule;

use PHPUnit\Framework\TestCase;
use ArrayTransform\Rule\SimpleRule;
use ArrayTransform\Rule\RuleInterface;

class SimpleRuleTest extends TestCase
{
    /**
     * @test
     */
    public function itImplementsTheRuleInterface()
    {
        $rule = new SimpleRule('sourceKey', 'targetKey');

        $this->assertInstanceOf(RuleInterface::class, $rule);
    }

    /**
     * @test
     */
    public function itIsReversible()
    {
        $rule = new SimpleRule('foo', 'bar');
        $reversed = $rule->reverse();

        $this->assertSame('bar', $reversed->getSourceKey());
        $this->assertSame('foo', $reversed->getTargetKey());
        $this->assertSame('baz', $reversed->resolveValue(['bar' => 'baz']));
    }

    /**
     * @test
     * @dataProvider getTestData
     */
    public function itResolvesValuesFromArray(string $sourceKey, string $targetKey, array $data, $expected)
    {
        $rule = new SimpleRule($sourceKey, $targetKey);

        $this->assertSame($expected, $rule->resolveValue($data));
    }

    /**
     * @return array
     */
    public function getTestData(): array
    {
        return [
            'integer mapping' => [
                'source',
                'target',
                [
                    'source' => 1,
                ],
                1,
            ],
            'string mapping' => [
                'source',
                'target',
                [
                    'source' => 'foo',
                ],
                'foo',
            ],
            'boolean mapping' => [
                'source',
                'target',
                [
                    'source' => true,
                ],
                true,
            ],
            'nonexistent source key' => [
                'source',
                'target',
                [],
                null,
            ]
        ];
    }
}
