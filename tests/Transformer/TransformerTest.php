<?php
/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayTransform\Tests\Transformer;

use PHPUnit\Framework\TestCase;
use Phake;
use ArrayTransform\Transformer\Transformer;
use ArrayTransform\Mapping\MappingInterface;
use ArrayTransform\Rule\SimpleRule;
use ArrayTransform\Rule\TypeRule;
use ArrayTransform\Rule\SimpleFormulaRule;
use ArrayTransform\Rule\DefaultsRule;
use ArrayTransform\Rule\ValueMappingRule;
use ArrayTransform\Rule\NotNullRule;

class TransformerTest extends TestCase
{
    /**
     * @var MappingInterface
     */
    private $mapping;

    /**
     * @var Transformer
     */
    private $transformer;

    public function setUp()
    {
        $this->mapping = Phake::mock(MappingInterface::class);

        $this->transformer = new Transformer($this->mapping);

        Phake::when($this->mapping)
            ->getKeySeparator()
            ->thenReturn('.');
    }

    /**
     * @test
     */
    public function itTransformsSimpleMapping()
    {
        Phake::when($this->mapping)
            ->getRules()
            ->thenReturn([
                new SimpleRule('source', 'target'),
                new SimpleRule('foo', 'bar'),
                new SimpleRule('nested.key', 'not_nested'),
            ]);

        $given = [
            'source' => 1,
            'foo' => 'baz',
            'unmapped_field' => 'i will not transform',
            'nested' => [
                'key' => 'value',
            ]
        ];

        $expected = [
            'target' => 1,
            'bar' => 'baz',
            'not_nested' => 'value',
        ];

        $this->assertSame($expected, $this->transformer->transform($given));
    }

    /**
     * @test
     */
    public function itTransformsComplexMapping()
    {
        $valueMappingRule = new ValueMappingRule(new SimpleRule('foo', 'bar'));
        $valueMappingRule->addValueMapping('asdf', 'fdsa');

        Phake::when($this->mapping)
            ->getRules()
            ->thenReturn([
                new TypeRule(
                    new SimpleFormulaRule(
                        new SimpleRule('nested.key', 'not_nested'),
                        '(not_nested * 2) - 1',
                        '(nested.key + 1) / 2'
                    ),
                    'int',
                    'float'
                ),
                new DefaultsRule(
                    new SimpleRule('key', 'other_key'),
                    'key_default',
                    'other_key_default'
                ),
                $valueMappingRule,
                new NotNullRule(
                    new SimpleRule('one', 'two'),
                    false,
                    true
                ),
                new SimpleRule('three', 'four'),
            ]);

        $given = [
            'nested' => [
                'key' => 3,
            ],
            'foo' => 'asdf',
        ];

        $expected = [
            'not_nested' => 2.0,
            'other_key' => 'key_default',
            'bar' => 'fdsa',
            'four' => null,
        ];

        $this->assertSame($expected, $this->transformer->transform($given));
    }

    /**
     * @test
     */
    public function itReverseTransformsSimpleMapping()
    {
        Phake::when($this->mapping)
            ->getReverseRules()
            ->thenReturn([
                new SimpleRule('mapped_to_empty_string', ''),
                new SimpleRule('source', 'target'),
                new SimpleRule('foo', 'bar'),
                new SimpleRule('nested.key', 'not_nested'),
            ]);

        $given = [
            'source' => 1,
            'foo' => 'baz',
            'unmapped_field' => 'i will not transform',
            'nested' => [
                'key' => 'value',
            ]
        ];

        $expected = [
            'target' => 1,
            'bar' => 'baz',
            'not_nested' => 'value',
        ];

        $this->assertSame($expected, $this->transformer->reverseTransform($given));
    }

    /**
     * @test
     */
    public function itReverseTransformsComplexMapping()
    {
        $valueMappingRule = new ValueMappingRule(new SimpleRule('foo', 'bar'));
        $valueMappingRule->addValueMapping('asdf', 'fdsa');

        Phake::when($this->mapping)
            ->getReverseRules()
            ->thenReturn([
                (new TypeRule(
                    new SimpleFormulaRule(
                        new SimpleRule('nested.key', 'not_nested'),
                        '(not_nested * 2) - 1',
                        '(nested.key + 1) / 2'
                    ),
                    'int',
                    'float'
                ))->reverse(),
                (new DefaultsRule(
                    new SimpleRule('key', 'other_key'),
                    'key_default',
                    'other_key_default'
                ))->reverse(),
                $valueMappingRule->reverse()
            ]);

        $given = [
            'not_nested' => 2.0,
            'bar' => 'fdsa',
        ];

        $expected = [
            'nested' => [
                'key' => 3,
            ],
            'key' => 'other_key_default',
            'foo' => 'asdf',
        ];

        $this->assertSame($expected, $this->transformer->reverseTransform($given));
    }
}
