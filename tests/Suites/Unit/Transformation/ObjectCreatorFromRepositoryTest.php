<?php

namespace Tests\Suites\Unit\Transformation;

use PHPUnit\Framework\MockObject\MockObject;
use stdClass;
use Tests\Support\Bases\PolicyDeferredTransformerTestCase;
use WebTheory\Factory\Exception\UnresolvableItemException;
use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;
use WebTheory\Factory\Transformation\ObjectCreatorFromRepository;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

class ObjectCreatorFromRepositoryTest extends PolicyDeferredTransformerTestCase
{
    use HasExpectedTypes;

    protected ObjectCreatorFromRepository $sut;

    protected string $classKey = '@create';

    /**
     * @var MockObject&FlexFactoryRepositoryInterface
     */
    protected FlexFactoryRepositoryInterface $repository;

    /**
     *  @var MockObject&FlexFactoryInterface
     */
    protected FlexFactoryInterface $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $mock = [$this, 'createMock'];

        $this->factory = $mock(FlexFactoryInterface::class);

        $this->sut = new ObjectCreatorFromRepository(
            $this->repository = $mock(FlexFactoryRepositoryInterface::class),
            $this->policy
        );
    }

    /**
     * @test
     */
    public function it_can_resolve_arguments_as_objects_via_repository()
    {
        # Arrange
        $key = $this->dummyArg();
        $create = $this->dummyArg();
        $args = $this->fakeMap(
            ['prop1', 'prop2', 'prop3'],
            ['name', 'streetName', 'jobTitle'],
        );
        $value = [$this->classKey => $create] + $args;

        $expected = new stdClass();

        $this->configurePolicy(true, $create, $args);

        # Expect
        $this->repository->method('getTypeFactory')
            ->with($key)
            ->willReturn($this->factory);

        $this->factory->method('create')
            ->with($create, $args)
            ->willReturn($expected);

        # Act
        $result = $this->sut->transformArg($key, $value, $this->parameter);

        # Assert
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_it_cannot_resolve_an_argument_to_a_class()
    {
        $key = 'value_one';
        $value = [$this->classKey => $this->dummyArg()];

        $this->configurePolicy(true);

        $this->repository->method('getTypeFactory')->willReturn(false);

        # Expect
        $this->expectException(UnresolvableItemException::class);

        # Act
        $this->sut->transformArg($key, $value, $this->parameter);
    }
}
