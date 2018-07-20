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
        $expected = ['somekey' => ['inverse' => 'someotherkey']];

        $this->assertSame($expected, $this->loader->load($this->getFixture('valid.yaml')));
    }

    /**
     * @test
     */
    public function itThrowsParseExceptionIfRootNotIsNotPresent()
    {
        $this->expectException(ParseException::class);
        $this->loader->load($this->getFixture('invalid.yaml'));
    }

    /**
     * @test
     */
    public function itThrowsParseExceptionIfRootNodeIsNotAnArray()
    {
        $this->expectException(ParseException::class);
        $this->loader->load($this->getFixture('invalid2.yaml'));
    }

    /**
     * @param string $fileName
     * @return string
     */
    private function getFixture(string $fileName): string
    {
        return \sprintf(
            '%s/Fixtures/%s',
            \dirname(\dirname(__FILE__)),
            $fileName
        );
    }
}
