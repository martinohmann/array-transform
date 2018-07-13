<?php

namespace ArrayTransform\Tests\Key;

use PHPUnit\Framework\TestCase;
use ArrayTransform\Key\KeyParser;

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
}
