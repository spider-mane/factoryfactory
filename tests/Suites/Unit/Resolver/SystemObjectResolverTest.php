<?php

namespace Tests\Suites\Unit\Resolver;

use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use ReflectionParameter;
use Tests\Support\Fixtures\DummyClass;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Interfaces\UniversalDependencyResolverInterface;
use WebTheory\Factory\Resolver\SystemObjectResolver;

class SystemObjectResolverTest extends UnitTestCase
{
    protected const RESOLVER_RESOLUTION_METHOD = 'resolve';

    protected const PARAMETER_GET_CLASS_METHOD = 'getDeclaringClass';

    protected const REFLECTION_CLASS_GET_NAME_METHOD = 'getName';

    protected SystemObjectResolver $sut;

    /**
     * @var MockObject&UniversalDependencyResolverInterface
     */
    protected UniversalDependencyResolverInterface $resolver;

    /**
     * @var MockObject&ReflectionParameter
     */
    protected ReflectionParameter $reflectionParameter;

    /**
     * @var MockObject&ReflectionClass
     */
    protected ReflectionClass $reflectionClass;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reflectionParameter = $this->createMock(ReflectionParameter::class);
        $this->reflectionClass = $this->createMock(ReflectionClass::class);

        $this->sut = new SystemObjectResolver(
            $this->resolver = $this->createMock(UniversalDependencyResolverInterface::class)
        );
    }

    /**
     * @test
     */
    public function it_returns_object_created_by_resolver()
    {
        $param = $this->reflectionParameter;
        $item = $this->fake->word();
        $query = $this->fake->word();
        $args = $this->fakeAutoKeyedMap(7, 'sentence');
        $class = DummyClass::class;
        $expected = new $class();

        $this->reflectionParameter->method(static::PARAMETER_GET_CLASS_METHOD)
            ->willReturn($this->reflectionClass);

        $this->reflectionClass->method(static::REFLECTION_CLASS_GET_NAME_METHOD)
            ->willReturn($class);

        # Expect
        $this->resolver->expects($this->once())
            ->method(static::RESOLVER_RESOLUTION_METHOD)
            ->with($class, $item, $query, $args)
            ->willReturn($expected);

        # Act
        $result = $this->sut->resolveObject($item, $query, $args, $param);

        # Assert
        $this->assertSame($expected, $result);
    }
}
