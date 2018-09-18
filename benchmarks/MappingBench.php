<?php declare(strict_types=1);
/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayTransform\Benchmarks;

use ArrayTransform\Mapping\MappingFactory;
use PhpBench\Benchmark\Metadata\Annotations\BeforeClassMethods;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\ParamProviders;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

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
                'config' => [],
            ],
            [
                'config' => [
                    'foo[int]' => [
                        'inverse' => 'bar[string]',
                    ],
                ],
            ],
            [
                'config' => [
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
                'config' => [
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
            [
                'config' => [
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
                    'bar[float]' => [
                        'inverse' => 'foo.baz[float]',
                        'formula' => [
                            'direct' => 'foo.baz * 1000',
                            'inverse' => 'bar / 1000',
                        ],
                    ],
                    'some.key[string]' => [
                        'inverse' => 'some_key[string]',
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
    public function benchMappingFactory(array $params)
    {
        $this->factory->createMapping($params['config']);
    }
}
