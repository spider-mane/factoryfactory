<?php

namespace Tests\Suites\Unit\Resolver;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\Support\Fixtures\DummyClass;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Exception\UnresolvableTypeException;
use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;
use WebTheory\Factory\Interfaces\TypeCasterInterface;
use WebTheory\Factory\Resolver\TypeFlexFactoryRouter;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

class TypeFactoryRepositoryRouterTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected const RESOLVER_RESOLUTION_METHOD = 'getTypeFactory';

    protected const FACTORY_CREATION_METHOD = 'create';

    protected TypeFlexFactoryRouter $sut;

    /**
     * @var MockObject&FlexFactoryRepositoryInterface
     */
    protected FlexFactoryRepositoryInterface $repository;

    /**
     * @var MockObject&FlexFactoryInterface
     */
    protected FlexFactoryInterface $factory;

    /**
     * @var class-string
     */
    protected string $type;

    protected string $query;

    protected array $args;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = $this->createMock(FlexFactoryInterface::class);

        $this->type = DummyClass::class;
        $this->query = $this->dummyArg();
        $this->args = $this->dummyArgs();

        $this->sut = new TypeFlexFactoryRouter(
            $this->repository = $this->createMock(FlexFactoryRepositoryInterface::class)
        );
    }

    protected function defineExpectedTypesData(callable $ds): array
    {
        return [
            $ds($i = TypeCasterInterface::class) => [$i],
        ];
    }

    /**
     * @test
     */
    public function it_returns_a_object_created_factory()
    {
        $type = $this->type;
        $query = $this->query;
        $args = $this->args;
        $expected = new DummyClass();

        $this->repository->expects($this->once())
            ->method(static::RESOLVER_RESOLUTION_METHOD)
            ->with($type)
            ->willReturn($this->factory);

        $this->factory->expects($this->once())
            ->method(static::FACTORY_CREATION_METHOD)
            ->with($query, $args)
            ->willReturn($expected);

        $result = $this->sut->cast($type, $query, $args);

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_type_is_not_mapped_to_a_factory()
    {
        $this->expectException(UnresolvableTypeException::class);

        $this->sut->cast($this->type, $this->query, []);
    }
}
