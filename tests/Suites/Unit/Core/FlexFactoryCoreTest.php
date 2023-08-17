<?php

namespace Tests\Suites\Unit\Core;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\Support\Fixtures\DummyClass;
use Tests\Support\Partials\HasExpectedTypes;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Core\FlexFactoryCore;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\FactoryEngineInterface;
use WebTheory\Factory\Interfaces\FixedFactoryInterface;
use WebTheory\Factory\Interfaces\FixedFactoryRepositoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryCoreInterface;

class FlexFactoryCoreTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected const REPOSITORY_QUERY_METHOD = 'getClassFactory';

    protected const FACTORY_CREATION_METHOD = 'create';

    protected const RESOLVER_QUERY_METHOD = 'getClass';

    protected const ENGINE_CREATION_METHOD = 'generate';

    protected FlexFactoryCore $sut;

    /**
     * @var MockObject&ClassResolverInterface
     */
    protected ClassResolverInterface $classResolver;

    /**
     * @var MockObject&FactoryEngineInterface
     */
    protected FactoryEngineInterface $engine;

    /**
     * @var MockObject&FixedFactoryRepositoryInterface
     */
    protected FixedFactoryRepositoryInterface $repository;

    /**
     * @var MockObject&FixedFactoryInterface
     */
    protected FixedFactoryInterface $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $mock = [$this, 'createMock'];

        $this->factory = $mock(FixedFactoryInterface::class);

        $this->sut = new FlexFactoryCore(
            $this->classResolver = $mock(ClassResolverInterface::class),
            $this->engine = $mock(FactoryEngineInterface::class),
            $this->repository = $mock(FixedFactoryRepositoryInterface::class)
        );
    }

    protected function defineExpectedTypesData(callable $ds): array
    {
        return [
            $ds($i = FlexFactoryCoreInterface::class) => [$i],
        ];
    }

    /**
     * @test
     */
    public function it_returns_object_created_by_factory_resolved_via_argument()
    {
        $query = $this->fake->word();
        $args = $this->fakeAutoKeyedMap(10, 'sentence');
        $expected = new DummyClass();

        $this->repository->method(static::REPOSITORY_QUERY_METHOD)
            ->with($query)
            ->willReturn($this->factory);

        $this->factory->method(static::FACTORY_CREATION_METHOD)
            ->with($args)
            ->willReturn($expected);

        $result = $this->sut->process($query, $args);

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function it_returns_object_created_by_factory_resolved_via_resolved_class_name()
    {
        $query = $this->fake->word();
        $args = $this->fakeAutoKeyedMap(10, 'sentence');
        $class = DummyClass::class;
        $expected = new $class();

        $this->repository->expects($this->exactly(2))
            ->method(static::REPOSITORY_QUERY_METHOD)
            ->willReturnOnConsecutiveCalls(false, $this->factory);

        $this->classResolver->method(static::RESOLVER_QUERY_METHOD)
            ->with($query)
            ->willReturn($class);

        $this->factory->method(static::FACTORY_CREATION_METHOD)
            ->with($args)
            ->willReturn($expected);

        $result = $this->sut->process($query, $args);

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function it_returns_object_created_by_engine()
    {
        $query = $this->fake->word();
        $args = $this->fakeAutoKeyedMap(10, 'sentence');
        $class = DummyClass::class;
        $object = new $class();

        $this->classResolver->method(static::RESOLVER_QUERY_METHOD)
            ->with($query)
            ->willReturn($class);

        $this->engine->method(static::ENGINE_CREATION_METHOD)
            ->with($class, $args)
            ->willReturn($object);

        $result = $this->sut->process($query, $args);

        $this->assertSame($object, $result);
    }

    /**
     * @test
     */
    public function it_returns_false_if_it_cannot_resolve_the_query()
    {
        $this->repository->method(static::REPOSITORY_QUERY_METHOD)
            ->willReturn(false);

        $this->classResolver->method(static::RESOLVER_QUERY_METHOD)
            ->willReturn(false);

        $result = $this->sut->process($this->fake->word());

        $this->assertFalse($result);
    }
}
