<?php

namespace Tests\Suites\Unit\Resolver;

use PHPUnit\Framework\MockObject\MockObject;
use ReflectionParameter;
use Tests\Support\Fixtures\DummyClass;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Interfaces\DependencyResolverInterface;
use WebTheory\Factory\Interfaces\ObjectResolverInterface;
use WebTheory\Factory\Resolver\ClassEntryObjectResolver;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

class ClassEntryObjectResolverTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected const RESOLVER_RESOLUTION_METHOD = 'resolve';

    protected ClassEntryObjectResolver $sut;

    /**
     * @var MockObject&DependencyResolverInterface
     */
    protected DependencyResolverInterface $resolver;

    /**
     * @var MockObject&ReflectionParameter
     */
    protected ReflectionParameter $reflectionParameter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reflectionParameter = $this->createMock(ReflectionParameter::class);

        $this->sut = new ClassEntryObjectResolver(
            $this->resolver = $this->createMock(DependencyResolverInterface::class)
        );
    }

    protected function defineExpectedTypesData(callable $ds): array
    {
        return [
            $ds($t = ObjectResolverInterface::class) => [$t],
        ];
    }

    /**
     * @test
     */
    public function it_returns_the_object_created_by_resolver()
    {
        # Arrange
        $param = $this->reflectionParameter;
        $item = $this->dummyArg();
        $query = $this->dummyArg();
        $args = $this->fakeMap(
            ['prop1', 'prop2', 'prop3'],
            ['name', 'streetName', 'jobTitle'],
        );

        $expected = new DummyClass();

        # Expect
        $this->resolver->expects($this->once())
            ->method(static::RESOLVER_RESOLUTION_METHOD)
            ->with($item, $query, $args)
            ->willReturn($expected);

        # Act
        $result = $this->sut->resolveObject($item, $query, $args, $param);

        # Assert
        $this->assertSame($expected, $result);
    }
}
