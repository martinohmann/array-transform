<?php

namespace ArrayTransform\Tests\Transformer;

use PHPUnit\Framework\TestCase;
use Phake;
use ArrayTransform\Transformer\Transformer;
use ArrayTransform\Mapping\MappingInterface;
use ArrayTransform\Rule\SimpleRule;

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
    public function itReverseTransformsSimpleMapping()
    {
        Phake::when($this->mapping)
            ->getReverseRules()
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

        $this->assertSame($expected, $this->transformer->reverseTransform($given));
    }
}
