<?php

namespace Tests\Suites\Unit;

use DateTime;
use Tests\Support\Dummies\DummyFactory;
use Tests\Support\TestCase;
use WebTheory\Factory\Traits\SmartFactoryTrait;

class SmartFactoryTraitTest extends TestCase
{
    public function generateTestInstance()
    {
        return new class
        {
            use SmartFactoryTrait;

            /**
             * @param $args
             * @return DummyFactory
             */
            public function create($args): DummyFactory
            {
                $object = $this->build(DummyFactory::class, $args);

                return $object;
            }
        };
    }

    /**
     * Test that true does in fact equal true
     */
    public function testCreatesAndConfiguresSpecifiedClass()
    {
        $factory = $this->generateTestInstance();

        $args = [
            'value_one' => 'foo',
            'value_three' => 'bar',
            'value_five' => 45616,
            'value_four' => [new DateTime(), new DateTime(), new DateTime()],
            'value_two' => new DateTime()
        ];

        $instance = $factory->create($args);

        $this->assertEquals($args['value_one'], $instance->getValueOne());
        $this->assertEquals($args['value_two'], $instance->getValueTwo());
        $this->assertEquals($args['value_three'], $instance->getValueThree());
        $this->assertEquals($args['value_four'], $instance->getValueFour());
        $this->assertEquals($args['value_five'], $instance->getValueFive());
    }
}
