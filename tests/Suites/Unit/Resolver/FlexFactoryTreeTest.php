<?php

namespace Tests\Suites\Unit\Resolver;

use PHPUnit\Framework\MockObject\MockObject;
use stdClass;
use Tests\Support\Fixtures\DummyClass;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Exception\UnresolvableEntryException;
use WebTheory\Factory\Exception\UnresolvableSubjectException;
use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;
use WebTheory\Factory\Interfaces\UniversalDependencyResolverInterface;
use WebTheory\Factory\Resolver\FlexFactoryTree;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

class FlexFactoryTreeTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected const REPOSITORY_GET_FACTORY_METHOD = 'getTypeFactory';

    protected const FACTORY_CREATION_METHOD = 'create';

    protected FlexFactoryTree $sut;

    /**
     * @var MockObject&FlexFactoryRepositoryInterface
     */
    protected FlexFactoryRepositoryInterface $repository;

    /**
     * @var MockObject&FlexFactoryInterface
     */
    protected FlexFactoryInterface $factory;

    protected string $class = DummyClass::class;

    protected string $entry;

    protected string $query;

    protected function setUp(): void
    {
        parent::setUp();

        $mock = [$this, 'createMock'];

        $this->repository = $mock(FlexFactoryRepositoryInterface::class);
        $this->factory = $mock(FlexFactoryInterface::class);

        $this->entry = $this->dummyArg();
        $this->query = $this->dummyArg();

        $this->sut = new FlexFactoryTree([
            $this->class => $this->repository,
        ]);
    }

    protected function defineExpectedTypesData(callable $ds): array
    {
        return [
            $ds($i = UniversalDependencyResolverInterface::class) => [$i],
        ];
    }

    /**
     * @test
     */
    public function it_returns_a_object_created_by_factory_resolved_by_repository()
    {
        $class = $this->class;
        $entry = $this->entry;
        $query = $this->query;
        $args = $this->fakeAutoKeyedMap(5, 'sentence');
        $expected = new DummyClass();

        $this->repository->expects($this->once())
            ->method(static::REPOSITORY_GET_FACTORY_METHOD)
            ->with($entry)
            ->willReturn($this->factory);

        $this->factory->expects($this->once())
            ->method(static::FACTORY_CREATION_METHOD)
            ->with($query, $args)
            ->willReturn($expected);

        $result = $this->sut->resolve($class, $entry, $query, $args);

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_class_is_not_mapped_to_a_repository()
    {
        $this->expectException(UnresolvableSubjectException::class);

        $this->sut->resolve(stdClass::class, $this->entry, $this->query, []);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_entry_is_not_mapped_to_a_factory()
    {
        $this->repository->method(static::REPOSITORY_GET_FACTORY_METHOD)
            ->willReturn(false);

        $this->expectException(UnresolvableEntryException::class);

        $this->sut->resolve($this->class, $this->entry, $this->query, []);
    }
}
