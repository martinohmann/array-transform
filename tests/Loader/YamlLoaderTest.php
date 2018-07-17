<?php

namespace ArrayTransform\Tests\Loader;

use PHPUnit\Framework\TestCase;
use ArrayTransform\Loader\YamlLoader;
use ArrayTransform\Exception\ParseException;

class YamlLoaderTest extends TestCase
{
    /**
     * @var YamlLoader
     */
    private $loader;

    public function setUp()
    {
        $this->loader = new YamlLoader();
    }

    /**
     * @test
     */
    public function itLoadsValidYamlFileContentIntoArray()
    {
        $fixture = dirname(dirname(__FILE__)).'/fixtures/valid.yaml';
        $expected = [
            'array_transform' => [],
        ];

        $this->assertSame($expected, $this->loader->load($fixture));
    }

    /**
     * @test
     */
    public function itThrowsParseExceptionIfFileContentsAreNotAnArryStructure()
    {
        $fixture = dirname(dirname(__FILE__)).'/fixtures/invalid.yaml';

        $this->expectException(ParseException::class);
        $this->loader->load($fixture);
    }
}
