<?php
/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayTransform\Tests\Util;

use ArrayTransform\Util\ArrayUtil;
use PHPUnit\Framework\TestCase;

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
