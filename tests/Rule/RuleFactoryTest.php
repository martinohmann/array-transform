<?php

namespace ArrayTransform\Tests\Rule;

use PHPUnit\Framework\TestCase;
use ArrayTransform\Rule\RuleFactory;
use ArrayTransform\Exception\MappingException;
use ArrayTransform\Rule\TypeRule;
use ArrayTransform\Key\KeyParser;
use ArrayTransform\Rule\RuleInterface;
use ArrayTransform\Rule\SimpleRule;
use ArrayTransform\Rule\SimpleFormulaRule;
use ArrayTransform\Rule\ValueMappingRule;

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

        $this->assertSame($directKey, $rule->getSourceKey());
        $this->assertSame($config['inverse'], $rule->getTargetKey());
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
    public function itCreatesSimpleFormulaRuleWhenAtLeaseOneFormulaIsDefined(
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

        $this->assertSame(42, $rule->resolveValue(['foo' => 13]));
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
}
