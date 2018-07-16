<?php

namespace ArrayTransform\Tests\Mapping;

use PHPUnit\Framework\TestCase;
use ArrayTransform\Mapping\MappingFactory;
use ArrayTransform\Exception\MappingException;

class MappingFactoryTest extends TestCase
{
    /**
     * @var MappingFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new MappingFactory();
    }

    /**
     * @test
     * @dataProvider getInvalidConfigTestData
     */
    public function itShouldThrowExceptionForInvalidConfig(
        array $config,
        string $exceptionClass = MappingException::class
    ) {
        $this->expectException($exceptionClass);
        $this->factory->createMapping($config);
    }

    /**
     * @return array
     */
    public function getInvalidConfigTestData(): array
    {
        return [
            'root node missing' => [
                [],
            ],
            'root node not an array' => [
                [
                    'array_transform' => 'foo'
                ],
            ],
            'non-string keys' => [
                [
                    'array_transform' => [
                        0 => [],
                    ],
                ],
            ],
            'non-array key config' => [
                [
                    'array_transform' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getKeySeparatorTestData
     */
    public function itSetsKeySeparatorFromConfig(array $config, string $expected)
    {
        $mapping = $this->factory->createMapping($config);

        $this->assertSame($expected, $mapping->getKeySeparator());
    }

    /**
     * @return array
     */
    public function getKeySeparatorTestData(): array
    {
        return [
            'no key separator configured' => [
                [
                    'array_transform' => [],
                ],
                '.',
            ],
            'key separator configured' => [
                [
                    'array_transform' => [
                        '_global' => [
                            'keySeparator' => '#',
                        ]
                    ],
                ],
                '#',
            ],
            'numeric key separator configured' => [
                [
                    'array_transform' => [
                        '_global' => [
                            'keySeparator' => 123,
                        ]
                    ],
                ],
                '123',
            ],
        ];
    }

    /**
     * @test
     */
    public function itCreatesMappingWithRules()
    {
        $config = [
            'array_transform' => [
                '_global' => [
                    'keySeparator' => '.',
                ],
                '_defaults' => [],
                'foo[int]' => [
                    'inverse' => 'bar[string]',
                ],
                'bar' => [
                    'inverse' => 'baz',
                ],
                'kilograms' => [
                    'inverse' => 'grams',
                    'formula' => [
                        'direct' => 'grams / 1000',
                        'inverse' => 'kilograms * 1000',
                    ],
                ],
            ],
        ];

        $mapping = $this->factory->createMapping($config);

        $this->assertCount(3, $mapping->getRules());
    }
}
