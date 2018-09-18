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

use ArrayTransform\Rule\RuleFactory;
use PhpBench\Benchmark\Metadata\Annotations\BeforeClassMethods;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\ParamProviders;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

/**
 * @BeforeMethods({"init"})
 */
class RuleBench
{
    /**
     * @var RuleFactory
     */
    private $factory;

    /**
     * @return void
     */
    public function init()
    {
        $this->factory = new RuleFactory();
    }

    /**
     * @return array
     */
    public function provideKeys(): array
    {
        return [
            ['key' => 'foo[string]'],
            ['key' => 'foo[int]'],
            ['key' => 'foo'],
            ['key' => '']
        ];
    }

    /**
     * @return array
     */
    public function provideConfigs(): array
    {
        return [
            [
                'config' => [
                    'inverse' => 'bar',
                ],
            ],
            [
                'config' => [
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
            [
                'config' => [
                    'inverse' => 'bar[float]',
                    'formula' => [
                        'direct' => 'bar / 10',
                        'inverse' => 'foo * 10',
                    ],
                ],
            ],
        ];
    }

    /**
     * @Revs(100)
     * @Iterations(5)
     * @ParamProviders({"provideKeys","provideConfigs"})
     */
    public function benchRuleFactory(array $params)
    {
        $this->factory->createRule($params['key'], $params['config']);
    }
}
