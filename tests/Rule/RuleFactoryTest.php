<?php
/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayTransform\Tests\Rule;

use ArrayTransform\Exception\MappingException;
use ArrayTransform\Key\KeyParser;
use ArrayTransform\Rule\DefaultsRule;
use ArrayTransform\Rule\NotNullRule;
use ArrayTransform\Rule\RuleFactory;
use ArrayTransform\Rule\RuleInterface;
use ArrayTransform\Rule\SimpleFormulaRule;
use ArrayTransform\Rule\SimpleRule;
use ArrayTransform\Rule\TypeRule;
use ArrayTransform\Rule\ValueMappingRule;
use PHPUnit\Framework\TestCase;

class RuleFactoryTest extends TestCase
{
    /**
     * @var RuleFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new RuleFactory();
    }

    /**
     * @test
     */
    public function itCreatesSimpleRuleFromConfig()
    {
        $directKey = 'foo';

        $config = [
            'inverse' => 'bar',
        ];

        $rule = $this->factory->createRule($directKey, $config);

        $this->assertSame($config['inverse'], $rule->getSourceKey());
        $this->assertSame($directKey, $rule->getTargetKey());
    }

    /**
     * @test
     * @dataProvider getKeysTestData
     */
    public function itAllowsOneOfTheKeysToBeEmpty(
        string $directKey,
        array $config,
        bool $expectException = false
    ) {
        if ($expectException) {
            $this->expectException(MappingException::class);
        }

        $rule = $this->factory->createRule($directKey, $config);

        $this->assertInstanceOf(RuleInterface::class, $rule);
    }

    /**
     * @return array
     */
    public function getKeysTestData(): array
    {
        return [
            'direct key empty' => [
                '',
                ['inverse' => 'inverseKey'],
            ],
            'inverse key empty' => [
                'directKey',
                ['inverse' => ''],
            ],
            'inverse key null' => [
                'directKey',
                ['inverse' => null],
            ],
            'missing inverse key' => [
                'directKey',
                [],
            ],
            'both keys empty' => [
                '',
                ['inverse' => ''],
                true,
            ],
            'direct key empty, inverse missing' => [
                '',
                [],
                true,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getDefaultsTestData
     */
    public function itCreatesDefaultsRuleWhenAtLeastOneDefaultIsDefined(
        string $directKey,
        array $config,
        bool $notRuleType = false
    ) {
        $rule = $this->factory->createRule($directKey, $config);

        if ($notRuleType) {
            $this->assertNotInstanceOf(DefaultsRule::class, $rule);
        } else {
            $this->assertInstanceOf(DefaultsRule::class, $rule);
        }
    }

    /**
     * @return array
     */
    public function getDefaultsTestData(): array
    {
        return [
            'direct default defined' => [
                'foo',
                [
                    'inverse' => 'bar',
                    'defaults' => [
                        'direct' => 3,
                    ],
                ],
            ],
            'inverse default defined' => [
                'foo',
                [
                    'inverse' => 'bar',
                    'defaults' => [
                        'inverse' => 'barbar',
                    ],
                ],
            ],
            'both defaults defined' => [
                'foo',
                [
                    'inverse' => 'bar',
                    'defaults' => [
                        'direct' => true,
                        'inverse' => 1.5,
                    ],
                ],
            ],
            'empty defaults config' => [
                'foo',
                [
                    'inverse' => 'bar',
                    'defaults' => [],
                ],
                true,
            ],
            'no defaults config' => [
                'foo',
                [
                    'inverse' => 'bar',
                ],
                true,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getTypesTestData
     */
    public function itCreatesTypeRuleWhenAtLeastOneOfTheKeysContainsAType(
        string $directKey,
        string $inverseKey,
        bool $notRuleType = false
    ) {
        $config = [
            'inverse' => $inverseKey,
        ];

        $rule = $this->factory->createRule($directKey, $config);

        if ($notRuleType) {
            $this->assertNotInstanceOf(TypeRule::class, $rule);
        } else {
            $this->assertInstanceOf(TypeRule::class, $rule);
        }
    }

    /**
     * @return array
     */
    public function getTypesTestData(): array
    {
        return [
            'both have type' => [
                'foo[string]',
                'bar[int]',
            ],
            'direct has type' => [
                'foo[string]',
                'bar',
            ],
            'inverse has type' => [
                'foo',
                'bar[int]',
            ],
            'no types' => [
                'foo',
                'bar',
                SimpleRule::class,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getFormulasTestData
     */
    public function itCreatesSimpleFormulaRuleWhenAtLeastOneFormulaIsDefined(
        string $directKey,
        array $config,
        bool $notRuleType = false
    ) {
        $rule = $this->factory->createRule($directKey, $config);

        if ($notRuleType) {
            $this->assertNotInstanceOf(SimpleFormulaRule::class, $rule);
        } else {
            $this->assertInstanceOf(SimpleFormulaRule::class, $rule);
        }
    }

    /**
     * @return array
     */
    public function getFormulasTestData(): array
    {
        return [
            'direct formula defined' => [
                'foo',
                [
                    'inverse' => 'bar',
                    'formula' => [
                        'direct' => 'bar * 10',
                    ],
                ],
            ],
            'inverse formula defined' => [
                'foo',
                [
                    'inverse' => 'bar',
                    'formula' => [
                        'inverse' => 'foo / 10',
                    ],
                ],
            ],
            'both formulas defined' => [
                'foo',
                [
                    'inverse' => 'bar',
                    'formula' => [
                        'direct' => 'bar * 10',
                        'inverse' => 'foo / 10',
                    ],
                ],
            ],
            'empty formula config' => [
                'foo',
                [
                    'inverse' => 'bar',
                    'formula' => [],
                ],
                true,
            ],
            'no formula config' => [
                'foo',
                [
                    'inverse' => 'bar',
                ],
                true,
            ],
        ];
    }

    /**
     * @test
     */
    public function itCreatesValueMappingRule()
    {
        $directKey = 'foo';
        $config = [
            'inverse' => 'bar',
            'value_mapping' => [
                'mapping' => [
                    [
                        'direct' => 13,
                        'inverse' => 42,
                    ],
                ],
            ],
        ];

        $rule = $this->factory->createRule($directKey, $config);

        $this->assertInstanceOf(ValueMappingRule::class, $rule);

        $this->assertSame(13, $rule->resolveValue(['bar' => 42]));
    }

    /**
     * @test
     * @dataProvider getValueMappingTestData
     */
    public function itThrowsExceptionIfValueMappingIsIncomplete(
        array $mapping,
        string $exceptionClass = MappingException::class
    ) {
        $config = [
            'inverse' => 'bar',
            'value_mapping' => [
                'mapping' => $mapping,
            ],
        ];

        $this->expectException($exceptionClass);
        $this->factory->createRule('foo', $config);
    }

    /**
     * @return array
     */
    public function getValueMappingTestData(): array
    {
        return [
            'missing direct key' => [
                [
                    [
                        'inverse' => 42,
                    ],
                ],
            ],
            'missing inverse key' => [
                [
                    [
                        'direct' => 13,
                    ],
                ],
            ],
            'missing keys' => [
                [
                    [],
                ],
            ],
            'partly valid mapping' => [
                [
                    [
                        'direct' => 13,
                        'inverse' => 42,
                    ],
                    [
                        'direct' => 12,
                    ],
                ],
            ],
        ];
    }

    /**
     * @test
     */
    public function formulaRuleTakesPrecedenceOverValueMappingRule()
    {
        $directKey = 'foo';
        $config = [
            'inverse' => 'bar',
            'formula' => [
                'direct' => 'bar / 10',
                'inverse' => 'foo * 10',
            ],
            'value_mapping' => [
                'mapping' => [
                    [
                        'direct' => 13,
                        'inverse' => 42,
                    ],
                ],
            ],
        ];

        $rule = $this->factory->createRule($directKey, $config);

        $this->assertInstanceOf(SimpleFormulaRule::class, $rule);
    }

    /**
     * @test
     * @dataProvider getNotNullTestData
     */
    public function itCreatesNotNullRuleWhenNotNullIsDefinedAtLeastForOneKey(
        string $directKey,
        array $config,
        bool $notRuleType = false
    ) {
        $rule = $this->factory->createRule($directKey, $config);

        if ($notRuleType) {
            $this->assertNotInstanceOf(NotNullRule::class, $rule);
        } else {
            $this->assertInstanceOf(NotNullRule::class, $rule);
        }
    }

    /**
     * @return array
     */
    public function getNotNullTestData(): array
    {
        return [
            'direct not-null defined' => [
                'foo',
                [
                    'inverse' => 'bar',
                    'not_null' => [
                        'direct' => true,
                    ],
                ],
            ],
            'inverse not-null config defined' => [
                'foo',
                [
                    'inverse' => 'bar',
                    'not_null' => [
                        'inverse' => true,
                    ],
                ],
            ],
            'both not-null configs defined' => [
                'foo',
                [
                    'inverse' => 'bar',
                    'not_null' => [
                        'direct' => true,
                        'inverse' => false,
                    ],
                ],
            ],
            'both not-null configs defined 2' => [
                'foo',
                [
                    'inverse' => 'bar',
                    'not_null' => [
                        'direct' => '1',
                        'inverse' => '0',
                    ],
                ],
            ],
            'empty not-null config' => [
                'foo',
                [
                    'inverse' => 'bar',
                    'not_null' => [],
                ],
                true,
            ],
            'no not-null config' => [
                'foo',
                [
                    'inverse' => 'bar',
                ],
                true,
            ],
        ];
    }
}
