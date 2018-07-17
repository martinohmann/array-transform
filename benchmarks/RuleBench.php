<?php declare(strict_types=1);

namespace ArrayTransform\Benchmarks;

use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use ArrayTransform\Rule\RuleFactory;
use PhpBench\Benchmark\Metadata\Annotations\ParamProviders;
use PhpBench\Benchmark\Metadata\Annotations\BeforeClassMethods;

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
