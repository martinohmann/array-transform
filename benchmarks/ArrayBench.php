<?php declare(strict_types=1);

namespace ArrayTransform\Benchmarks;

use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\ParamProviders;
use ArrayTransform\Util\ArrayUtil;

class ArrayBench
{
    /**
     * @return array
     */
    public function provideNestedArray(): array
    {
        return [
            [
                'array' => [
                    'some' => [
                        'very' => [
                            'deeply' => [
                                'nested' => [
                                    'array' => (object) 0,
                                ],
                            ],
                        ],
                        'deeply' => [
                            'nested' => [
                                'array' => true,
                            ],
                        ],
                        'nested' => [
                            'array' => 2.0,
                        ],
                    ],
                    'nested' => [
                        'array' => 'three',
                    ],
                    'array' => 4,
                ],
            ],
        ];
    }

    /**
     * @Revs(1000)
     * @Iterations(10)
     * @ParamProviders({"provideNestedArray"})
     */
    public function benchArrayFlatten(array $params)
    {
        ArrayUtil::flatten($params['array']);
    }

    /**
     * @return array
     */
    public function provideFlatArray(): array
    {
        return [
            [
                'array' => [
                    'some.very.deeply.nested.array' => (object) 0,
                    'some.deeply.nested.array' => true,
                    'some.nested.array' => 2.0,
                    'nested.array' => 'three',
                    'array' => 4,
                ],
            ],
        ];
    }

    /**
     * @Revs(1000)
     * @Iterations(10)
     * @ParamProviders({"provideFlatArray"})
     */
    public function benchArrayUnflatten(array $params)
    {
        ArrayUtil::unflatten($params['array']);
    }
}
