<?php

declare(strict_types=1);

namespace Tests\Suites\Unit\Engine;

use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionParameter;
use Tests\Support\Fixtures\DummyClass;
use Tests\Support\Fixtures\StubClassWithConstructorAndSetters;
use Tests\Support\Fixtures\StubClassWithFluentSetters;
use Tests\Support\Fixtures\StubClassWithMixedVariadicConstructor;
use Tests\Support\Fixtures\StubClassWithoutSetters;
use Tests\Support\Fixtures\StubClassWithSetters;
use Tests\Support\Fixtures\StubClassWithVariadicArrayConstructor;
use Tests\Support\Fixtures\StubClassWithVariadicArraySetter;
use Tests\Support\Fixtures\StubClassWithVariadicConstructor;
use Tests\Support\Fixtures\StubClassWithVariadicSetter;
use Tests\Support\Fixtures\StubClassWithWithers;
use Tests\Support\Partials\HasExpectedTypes;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Engine\FactoryEngine;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;
use WebTheory\Factory\Interfaces\FactoryEngineInterface;

class FactoryEngineTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected const TRANSFORMATION_METHOD = 'transformArg';

    protected const GET_CLASS_METHOD = 'getClass';

    protected const GET_ARGS_METHOD = 'getArgs';

    protected FactoryEngine $sut;

    /**
     * @var MockObject&ArgValueTransformerInterface
     */
    protected ArgValueTransformerInterface $valueTransformer;

    /**
     * @var MockObject&ClassArgumentInterface
     */
    protected ClassArgumentInterface $classArgument;

    protected function assertObjectMatchesArgs(object $object, array $args, string $class): void
    {
        $this->assertInstanceOf($class, $object);

        foreach ($args as $key => $val) {
            $this->assertSame($val, $this->invokeValueGetter($object, $key));
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        $mock = [$this, 'createMock'];

        $this->valueTransformer = $mock(ArgValueTransformerInterface::class);
        $this->classArgument = $mock(ClassArgumentInterface::class);

        $this->sut = new FactoryEngine();
    }

    protected function getSutWithArgValueTransformer(): FactoryEngine
    {
        return new FactoryEngine(null, null, $this->valueTransformer);
    }

    protected function getValueGetter(string $prop): string
    {
        return 'get' . $this->caseConverter->convert($prop)->toPascal();
    }

    protected function invokeValueGetter(object $object, string $prop): mixed
    {
        return $object->{$this->getValueGetter($prop)}();
    }

    protected function getDummyArgs(string ...$types): array
    {
        $types = $types ?: ['word', 'sentence'];
        $keys = [
            'value_one',
            'value_two',
            'value_three',
            'value_four',
            'value_five',
        ];

        return $this->uniqueMap($keys, $types);
    }

    protected function defineExpectedTypesData(callable $ds): array
    {
        return [
            $ds($i = FactoryEngineInterface::class) => [$i],
        ];
    }

    /**
     * @test
     * @dataProvider objectCreationData
     */
    public function it_creates_an_object_from_an_array_of_arguments(string $class, array $args)
    {
        $result = $this->sut->generate($class, $args);

        $this->assertObjectMatchesArgs($result, $args, $class);
    }

    public function objectCreationData(): array
    {
        $this->initFaker();

        $generator = fn () => $this->unique->word();
        $keys = [
            'value_one',
            'value_two',
            'value_three',
            'value_four',
            'value_five',
        ];
        $dummyArgs = fn () => $this->dummyMap($generator, $keys);

        $ds = fn (string $via) => $this->ds()
            ->set('via', $via)
            ->get();

        return [
            $ds('setter<set>') => [
                'class' => StubClassWithSetters::class,
                'args' => $dummyArgs(),
            ],

            $ds('setter<with>') => [
                'class' => StubClassWithWithers::class,
                'args' => $dummyArgs(),
            ],

            $ds('setter<@fluent>') => [
                'class' => StubClassWithFluentSetters::class,
                'args' => $dummyArgs(),
            ],

            $ds('constructor') => [
                'class' => StubClassWithoutSetters::class,
                'args' => $dummyArgs(),
            ],

            $ds('constructor+setter') => [
                'class' => StubClassWithConstructorAndSetters::class,
                'args' => $dummyArgs(),
            ],
        ];
    }

    /**
     * @test
     */
    public function it_can_instantiate_a_class_with_no_constructor()
    {
        $class = DummyClass::class;

        $result = $this->sut->generate($class);

        $this->assertInstanceOf($class, $result);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_it_cannot_set_a_property()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->sut->generate(DummyClass::class, [
            'unresolvable_arg' => $this->fake->sentence(),
        ]);
    }

    /**
     * @test
     * @dataProvider variadicValuesData
     */
    public function it_can_set_variadic_values(string $class, array $args)
    {
        $result = $this->sut->generate($class, $args);

        $this->assertObjectMatchesArgs($result, $args, $class);
    }

    public function variadicValuesData(): array
    {
        $this->initFaker();

        $generator = fn () => $this->fake->word();
        $dummyArgs = fn () => $this->dummyList($generator);

        $ds = fn (string $via, bool $mixed) => $this->ds()
            ->set('via', $via)
            ->set('mixed', $mixed)
            ->get();

        return [
            $ds('setter', false) => [
                'class' => StubClassWithVariadicSetter::class,
                'args' => ['value' => $dummyArgs()],
            ],

            $ds('constructor', false) => [
                'class' => StubClassWithVariadicConstructor::class,
                'args' => ['value' => $dummyArgs()],
            ],

            $ds('constructor', true) => [
                'class' => StubClassWithMixedVariadicConstructor::class,
                'args' => [
                    'value_one' => $this->fake->sentence(),
                    'value_two' => $dummyArgs(),
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider variadicParameterWithArrayTypeData
     */
    public function it_selectively_expands_array_argument_on_variadic_parameter_with_array_type(
        bool $expanded,
        string $class,
        array $args
    ) {
        $expected = $expanded ? $args : [
            array_key_first($args) => [reset($args)],
        ];

        $result = $this->sut->generate($class, $args);

        $this->assertObjectMatchesArgs($result, $expected, $class);
    }

    public function variadicParameterWithArrayTypeData(): array
    {
        $this->initFaker();

        $setterStub = StubClassWithVariadicArraySetter::class;
        $constructorStub = StubClassWithVariadicArrayConstructor::class;

        $generator = fn () => $this->fake->word();
        $dummyList = fn () => array_fill(0, 5, $this->dummyList($generator));

        $ds = fn (string $via, bool $expanded) => $this->ds()
            ->set('via', $via)
            ->set('expanded', $expanded)
            ->get();

        return [
            $ds('setter', true) => [
                'expanded' => true,
                'class' => $setterStub,
                'args' => ['value' => $dummyList()],
            ],

            $ds('setter', false) => [
                'expanded' => false,
                'class' => $setterStub,
                'args' => ['value' => [$generator(), ...$dummyList()]],
            ],

            $ds('constructor', true) => [
                'expanded' => true,
                'class' => $constructorStub,
                'args' => ['value' => $dummyList()],
            ],

            $ds('constructor', false) => [
                'expanded' => false,
                'class' => $constructorStub,
                'args' => ['value' => [$generator(), ...$dummyList()]],
            ],
        ];
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_it_cannot_resolve_a_constructor_arg()
    {
        $class = StubClassWithoutSetters::class;

        $this->expectException(LogicException::class);

        $this->sut->generate($class, []);
    }

    /**
     * @test
     * @dataProvider valueTransformationData
     */
    public function it_correctly_utilizes_value_transformer(string $class, array $args, mixed $transformation)
    {
        $key = array_key_first($args);
        $val = $args[$key];

        $this->sut = new FactoryEngine(null, null, $this->valueTransformer);

        # Expect
        $this->valueTransformer->method(static::TRANSFORMATION_METHOD)
            ->with($key, $val, new IsInstanceOf(ReflectionParameter::class))
            ->willReturn($transformation);

        # Act
        $result = $this->invokeValueGetter(
            $this->sut->generate($class, $args),
            $key
        );

        # Assert
        $this->assertSame($result, $transformation);
    }

    public function valueTransformationData(): array
    {
        $this->initFaker();

        $ds = fn (string $via) => $this->data('via', $via)->get();

        return [
            $ds('constructor') => [
                'class' => StubClassWithoutSetters::class,
                'args' => [
                    'value_one' => $this->fake->word(),
                ],
                'transform' => $this->fake->colorName(),
            ],

            $ds('setter') => [
                'class' => StubClassWithSetters::class,
                'args' => [
                    'value_one' => $this->fake->name(),
                ],
                'transform' => $this->fake->company(),
            ],
        ];
    }

    /**
     * @test
     * @dataProvider argTransformedIntoClassArgumentData
     */
    public function it_creates_defined_object_if_arg_is_transformed_into_class_argument_instance(
        string $initialClass,
        array $initialArgs
    ) {
        $keys = array_keys($initialArgs);

        $nestedClass = StubClassWithConstructorAndSetters::class;
        $nestedArgs = $this->getDummyArgs();

        $this->valueTransformer->method(static::TRANSFORMATION_METHOD)
            ->willReturnOnConsecutiveCalls($this->classArgument, ...$nestedArgs);

        $this->classArgument->method(static::GET_CLASS_METHOD)
            ->willReturn($nestedClass);

        $this->classArgument->method(static::GET_ARGS_METHOD)
            ->willReturn($nestedArgs);

        $this->sut = $this->getSutWithArgValueTransformer();

        $result = $this->invokeValueGetter(
            $this->sut->generate($initialClass, $initialArgs),
            reset($keys),
        );

        $this->assertObjectMatchesArgs($result, $nestedArgs, $nestedClass);
    }

    public function argTransformedIntoClassArgumentData(): array
    {
        $this->initFaker();

        $ds = fn (string $via) => $this->data('via', $via)->get();

        return [
            $ds('constructor') => [
                'class' => StubClassWithoutSetters::class,
                'key' => $this->getDummyArgs(),
            ],

            $ds('setter') => [
                'class' => StubClassWithSetters::class,
                'key' => $this->getDummyArgs(),
            ],
        ];
    }
}
