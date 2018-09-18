<?php
/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayTransform\Tests\Key;

use ArrayTransform\Exception\ParseException;
use ArrayTransform\Key\KeyParser;
use PHPUnit\Framework\TestCase;

class KeyParserTest extends TestCase
{
    /**
     * @var KeyParser
     */
    private $parser;

    public function setUp()
    {
        $this->parser = new KeyParser();
    }

    /**
     * @test
     */
    public function itParsesKeyWithType()
    {
        $key = 'somekey[string]';

        $parsed = $this->parser->parseKey($key);

        $this->assertSame('somekey', $parsed->getName());
        $this->assertTrue($parsed->hasType());
        $this->assertSame('string', $parsed->getType());
    }

    /**
     * @test
     */
    public function itParsesKeyWithoutType()
    {
        $key = 'somekey';

        $parsed = $this->parser->parseKey($key);

        $this->assertSame('somekey', $parsed->getName());
        $this->assertFalse($parsed->hasType());
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfTypeIsInvalid()
    {
        $this->expectException(ParseException::class);
        $this->parser->parseKey('foo[bar]');
    }
}
