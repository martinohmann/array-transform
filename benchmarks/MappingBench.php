<?php declare(strict_types=1);

namespace ArrayTransform\Benchmarks;

use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use ArrayTransform\Mapping\MappingFactory;
use PhpBench\Benchmark\Metadata\Annotations\ParamProviders;
use PhpBench\Benchmark\Metadata\Annotations\BeforeClassMethods;

/**
 * @BeforeMethods({"init"})
 */
class MappingBench
{
    /**
     * @var MappingFactory
     */
    private $factory;

    /**
     * @return void
     */
    public function init()
    {
        $this->factory = new MappingFactory();
    }

    /**
     * @return array
     */
    public function provideConfigs(): array
    {
        return [
            [
                'array_transform' => [],
            ],
            [
                'array_transform' => [
                    'foo[int]' => [
                        'inverse' => 'bar[string]',
                    ],
                ],
            ],
            [
                'array_transform' => [
                    '_global' => [
                        'keySeparator' => '.',
                    ],
                    '_defaults' => [],
                    'foo[float]' => [
                        'inverse' => 'bar[float]',
                        'formula' => [
                            'direct' => 'bar * 1000',
                            'inverse' => 'foo / 1000',
                        ],
                    ],
                ],
            ],
            [
                'array_transform' => [
                    '_global' => [
                        'keySeparator' => '.',
                    ],
                    '_defaults' => [],
                    'foo[string]' => [
                        'inverse' => 'bar[string]',
                        'value_mapping' => [
                            'behaviour' => 'pass_through',
                            'mapping' => [
                                [
                                    'direct' => 'A',
                                    'inverse' => 'b'
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @Revs(100)
     * @Iterations(5)
     * @ParamProviders({"provideConfigs"})
     */
    public function benchMappingFactory(array $config)
    {
        $this->factory->createMapping($config);
    }
}
