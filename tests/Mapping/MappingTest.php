<?php
/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayTransform\Tests\Mapping;

use ArrayTransform\Mapping\Mapping;
use ArrayTransform\Rule\RuleInterface;
use Phake;
use PHPUnit\Framework\TestCase;

class MappingTest extends TestCase
{
    /**
     * @test
     */
    public function itImplementsTheMappingInterface()
    {
        $this->assertInstanceOf(Mapping::class, new Mapping());
    }

    /**
     * @test
     */
    public function itSetsRulesFromArray()
    {
        $rules = [Phake::mock(RuleInterface::class)];

        $mapping = new Mapping($rules);

        $this->assertSame($rules, $mapping->getRules());
    }

    /**
     * @test
     */
    public function itOnlyAllowsRulesOfTypeRuleInterface()
    {
        $rules = [Phake::mock(RuleInterface::class), 'somestring'];

        $this->expectException(\InvalidArgumentException::class);
        new Mapping($rules);
    }

    /**
     * @test
     */
    public function itAddsGivenRule()
    {
        $mapping = new Mapping();
        $rule = Phake::mock(RuleInterface::class);

        $this->assertEmpty($mapping->getRules());

        $mapping->addRule($rule);

        $this->assertCount(1, $mapping->getRules());
        $this->assertSame($rule, $mapping->getRules()[0]);
    }

    /**
     * @test
     */
    public function itReversesRules()
    {
        $ruleMock = Phake::mock(RuleInterface::class);
        $reversedMock = Phake::mock(RuleInterface::class);

        Phake::when($ruleMock)->reverse()->thenReturn($reversedMock);

        $mapping = new Mapping([$ruleMock]);

        $this->assertCount(1, $mapping->getReverseRules());
        $this->assertSame($reversedMock, $mapping->getReverseRules()[0]);
    }
}
