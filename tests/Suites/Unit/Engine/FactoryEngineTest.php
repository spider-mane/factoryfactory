<?php

namespace Tests\Suites\Unit\Engine;

use Tests\Support\Fixtures\ClassWithNoConstructor;
use Tests\Support\Fixtures\StubClassWithFluentSetters;
use Tests\Support\Fixtures\StubClassWithoutSetters;
use Tests\Support\Fixtures\StubClassWithSetters;
use Tests\Support\Fixtures\StubClassWithWithers;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Engine\FactoryEngine;
use WebTheory\Factory\Interfaces\FactoryEngineInterface;

class FactoryEngineTest extends UnitTestCase
{
    protected FactoryEngine $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new FactoryEngine();
    }

    protected function getValueGetter(string $prop): string
    {
        return 'get' . $this->caseConverter->convert($prop)->toPascal();
    }

    protected function invokeValueGetter(object $object, string $prop): mixed
    {
        return $object->{$this->getValueGetter($prop)}();
    }

    /**
     * @test
     * @dataProvider requiredInterfacesData
     */
    public function it_implements_the_required_interfaces(string $interface)
    {
        $this->assertInstanceOf($interface, $this->sut);
    }

    public function requiredInterfacesData(): array
    {
        return [
            $this->iut($i = FactoryEngineInterface::class) => [$i],
        ];
    }

    /**
     * @test
     * @dataProvider objectCreationData
     */
    public function it_creates_an_object_from_an_array_of_arguments(string $class, array $args, array $expectations)
    {
        $created = $this->sut->generate($class, $args);

        foreach ($expectations as $key => $value) {
            $result = $this->invokeValueGetter($created, $key);

            $this->assertEqualsCanonicalizing($value, $result);
        }
    }

    public function objectCreationData(): array
    {
        $this->initFaker();

        $ds = fn (string $setter, bool $classes) => $this->ds()
            ->set('setter', $setter)
            ->set('classes', $classes ? 'true' : 'false');

        return [
            $ds('set', false)->get() => [
                'class' => StubClassWithSetters::class,
                'args' => $args = [
                    'value_one' => $this->unique->word(),
                    'value_two' => $this->unique->word(),
                    'value_three' => $this->unique->word(),
                    'value_four' => $this->unique->word(),
                    'value_five' => $this->unique->word(),
                ],
                'expectations' => $args,
            ],

            $ds('with', false)->get() => [
                'class' => StubClassWithWithers::class,
                'args' => $args = [
                    'value_one' => $this->unique->word(),
                    'value_two' => $this->unique->word(),
                    'value_three' => $this->unique->word(),
                    'value_four' => $this->unique->word(),
                    'value_five' => $this->unique->word(),
                ],
                'expectations' => $args,
            ],

            $ds('@fluent', false)->get() => [
                'class' => StubClassWithFluentSetters::class,
                'args' => $args = [
                    'value_one' => $this->unique->word(),
                    'value_two' => $this->unique->word(),
                    'value_three' => $this->unique->word(),
                    'value_four' => $this->unique->word(),
                    'value_five' => $this->unique->word(),
                ],
                'expectations' => $args,
            ],

            $ds('@constructor', false)->get() => [
                'class' => StubClassWithoutSetters::class,
                'args' => $args = [
                    'value_one' => $this->unique->word(),
                    'value_two' => $this->unique->word(),
                    'value_three' => $this->unique->word(),
                    'value_four' => $this->unique->word(),
                    'value_five' => $this->unique->word(),
                ],
                'expectations' => $args,
            ],
        ];
    }

    /**
     * @test
     */
    public function it_can_instantiate_a_class_with_no_constructor()
    {
        $class = ClassWithNoConstructor::class;

        $result = $this->sut->generate($class);

        $this->assertInstanceOf($class, $result);
    }
}
