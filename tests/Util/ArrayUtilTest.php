<?php

namespace ArrayTransform\Tests\Util;

use PHPUnit\Framework\TestCase;
use ArrayTransform\Util\ArrayUtil;

class ArrayUtilTest extends TestCase
{
    /**
     * @test
     * @dataProvider getArrayTestData
     */
    public function itFlattensArrays(array $given, array $expected, string $separator = '.')
    {
        $this->assertSame($expected, ArrayUtil::flatten($given, $separator));
    }

    /**
     * @test
     * @dataProvider getArrayTestData
     */
    public function itUnflattensArrays(array $expected, array $given, string $separator = '.')
    {
        $this->assertSame($expected, ArrayUtil::unflatten($given, $separator));
    }

    /**
     * @return array
     */
    public function getArrayTestData(): array
    {
        return [
            'simple array' => [
                ['foo' => 'bar'],
                ['foo' => 'bar'],
            ],
            'simple nested array' => [
                ['foo' => ['bar' => 'baz']],
                ['foo.bar' => 'baz'],
            ],
            'deeply nested array' => [
                ['foo' => ['bar' => ['baz' => ['key' => 'value']]]],
                ['foo.bar.baz.key' => 'value'],
            ],
            'different separator' => [
                ['foo' => ['bar' => ['baz' => ['key' => 'value']]]],
                ['foo#bar#baz#key' => 'value'],
                '#',
            ],
        ];
    }
}
