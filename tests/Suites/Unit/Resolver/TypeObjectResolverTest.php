<?php

namespace Tests\Suites\Unit\Resolver;

use PHPUnit\Framework\MockObject\MockObject;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;
use ReflectionUnionType;
use Tests\Support\Fixtures\DummyClass;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Exception\UnresolvableParameterTypeException;
use WebTheory\Factory\Interfaces\TypeCasterInterface;
use WebTheory\Factory\Resolver\TypeObjectResolver;

class TypeObjectResolverTest extends UnitTestCase
{
    protected const CASTER_RESOLUTION_METHOD = 'cast';

    protected const REFLECTION_PARAMETER_GET_TYPE_METHOD = 'getType';

    protected const REFLECTION_NAMED_TYPE_GET_NAME_METHOD = 'getName';

    protected TypeObjectResolver $sut;

    /**
     * @var MockObject&TypeCasterInterface
     */
    protected TypeCasterInterface $caster;

    /**
     * @var MockObject&ReflectionParameter
     */
    protected ReflectionParameter $reflectionParameter;

    /**
     * @var MockObject&ReflectionNamedType
     */
    protected ReflectionNamedType $reflectionNamedType;

    /**
     * @var MockObject&ReflectionUnionType
     */
    protected ReflectionUnionType $reflectionUnionType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reflectionParameter = $this->createMock(ReflectionParameter::class);
        $this->reflectionNamedType = $this->createMock(ReflectionNamedType::class);
        $this->reflectionUnionType = $this->createMock(ReflectionUnionType::class);

        $this->sut = new TypeObjectResolver(
            $this->caster = $this->createMock(TypeCasterInterface::class)
        );
    }

    /**
     * @test
     */
    public function it_returns_object_created_by_resolver()
    {
        $param = $this->reflectionParameter;
        $item = $this->dummyArg();
        $query = $this->dummyArg();
        $args = $this->dummyArgs();
        $type = DummyClass::class;
        $expected = new $type();

        $this->reflectionParameter->method(static::REFLECTION_PARAMETER_GET_TYPE_METHOD)
            ->willReturn($this->reflectionNamedType);

        $this->reflectionNamedType->method(static::REFLECTION_NAMED_TYPE_GET_NAME_METHOD)
            ->willReturn($type);

        # Expect
        $this->caster->expects($this->once())
            ->method(static::CASTER_RESOLUTION_METHOD)
            ->with($type, $query, $args)
            ->willReturn($expected);

        # Act
        $result = $this->sut->resolveObject($item, $query, $args, $param);

        # Assert
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     * @dataProvider invalidParameterTypeData
     */
    public function it_throws_an_exception_if_parameter_type_is_not_named_type(?ReflectionType $value)
    {
        $this->reflectionParameter->method(static::REFLECTION_PARAMETER_GET_TYPE_METHOD)
            ->willReturn($value);

        $this->reflectionParameter->method('getName')
            ->willReturn($this->fake->word());

        $this->expectException(UnresolvableParameterTypeException::class);

        $this->sut->resolveObject(
            $this->dummyArg(),
            $this->dummyArg(),
            $this->dummyArgs(),
            $this->reflectionParameter
        );
    }

    public function invalidParameterTypeData(): array
    {
        $this->init();

        $ds = fn (string $type) => $this->data('type', $type)->get();

        return [
            $ds('null') => [null],
            $ds('union') => [$this->createMock(ReflectionUnionType::class)],
        ];
    }
}
