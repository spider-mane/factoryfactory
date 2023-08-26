<?php

namespace Tests\Suites\Unit\Resolver;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\Support\Fixtures\DummyClass;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Exception\UnresolvableEntryException;
use WebTheory\Factory\Interfaces\DependencyResolverInterface;
use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;
use WebTheory\Factory\Resolver\FlexFactoryRouter;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

class FlexFactoryRouterTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected const RESOLVER_RESOLUTION_METHOD = 'getTypeFactory';

    protected const FACTORY_CREATION_METHOD = 'create';

    protected FlexFactoryRouter $sut;

    /**
     * @var MockObject&FlexFactoryRepositoryInterface
     */
    protected FlexFactoryRepositoryInterface $repository;

    /**
     * @var MockObject&FlexFactoryInterface
     */
    protected FlexFactoryInterface $factory;

    protected string $entry;

    protected string $query;

    protected array $args;

    protected function setUp(): void
    {
        parent::setUp();

        $mock = [$this, 'createMock'];

        $this->factory = $mock(FlexFactoryInterface::class);

        $this->entry = $this->dummyArg();
        $this->query = $this->dummyArg();
        $this->args = $this->fakeAutoKeyedMap(5, 'sentence');

        $this->sut = new FlexFactoryRouter(
            $this->repository = $mock(FlexFactoryRepositoryInterface::class)
        );
    }

    protected function defineExpectedTypesData(callable $ds): array
    {
        return [
            $ds($i = DependencyResolverInterface::class) => [$i],
        ];
    }

    /**
     * @test
     */
    public function it_returns_a_object_created_nested_resolver()
    {
        $entry = $this->entry;
        $query = $this->query;
        $args = $this->args;
        $expected = new DummyClass();

        $this->repository->expects($this->once())
            ->method(static::RESOLVER_RESOLUTION_METHOD)
            ->with($entry)
            ->willReturn($this->factory);

        $this->factory->expects($this->once())
            ->method(static::FACTORY_CREATION_METHOD)
            ->with($query, $args)
            ->willReturn($expected);

        $result = $this->sut->resolve($entry, $query, $args);

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_entry_is_not_mapped_to_a_factory()
    {
        $this->expectException(UnresolvableEntryException::class);

        $this->sut->resolve('invalid_entry', $this->query, []);
    }
}
