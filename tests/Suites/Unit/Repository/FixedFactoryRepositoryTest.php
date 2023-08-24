<?php

namespace Tests\Suites\Unit\Repository;

use ArrayObject;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Interfaces\FixedFactoryInterface;
use WebTheory\Factory\Interfaces\FixedFactoryRepositoryInterface;
use WebTheory\Factory\Repository\FixedFactoryRepository;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

class FixedFactoryRepositoryTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected FixedFactoryRepository $sut;

    /**
     * @var MockObject&FixedFactoryInterface
     */
    protected FixedFactoryInterface $factory;

    protected string $key;

    protected function setUp(): void
    {
        parent::setUp();

        $this->key = $this->dummyArg();
        $this->factory = $this->createMock(FixedFactoryInterface::class);

        $this->sut = new FixedFactoryRepository([
            $this->key => $this->factory,
        ]);
    }

    protected function defineExpectedTypesData(callable $ds): array
    {
        return [
            $ds($i = FixedFactoryRepositoryInterface::class) => [$i],
        ];
    }

    /**
     * @test
     */
    public function it_returns_requested_factory()
    {
        $result = $this->sut->getClassFactory($this->key);

        $this->assertSame($this->factory, $result);
    }

    /**
     * @test
     */
    public function it_returns_false_if_it_does_not_have_mapped_factory()
    {
        $this->assertFalse($this->sut->getClassFactory('invalid_factory'));
    }

    /**
     * @test
     */
    public function it_accepts_array_accessible_object_as_constructor_argument()
    {
        $accessible = new ArrayObject([]);

        $result = new FixedFactoryRepository($accessible);

        $this->assertInstanceOf(FixedFactoryRepository::class, $result);
    }
}
