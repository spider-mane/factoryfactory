<?php

namespace Tests\Suites\Unit\Repository;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\Support\Partials\HasExpectedTypes;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;
use WebTheory\Factory\Repository\FlexFactoryRepository;

class FlexFactoryRepositoryTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected FlexFactoryRepository $sut;

    /**
     * @var MockObject&FlexFactoryInterface
     */
    protected FlexFactoryInterface $factory;

    protected string $key;

    protected function setUp(): void
    {
        parent::setUp();

        $this->key = $this->dummyArg();
        $this->factory = $this->createMock(FlexFactoryInterface::class);

        $this->sut = new FlexFactoryRepository([
            $this->key => $this->factory,
        ]);
    }

    protected function defineExpectedTypesData(callable $ds): array
    {
        return [
            $ds($i = FlexFactoryRepositoryInterface::class) => [$i],
        ];
    }

    /**
     * @test
     */
    public function it_returns_requested_factory()
    {
        $result = $this->sut->getTypeFactory($this->key);

        $this->assertSame($this->factory, $result);
    }

    /**
     * @test
     */
    public function it_returns_false_if_it_does_not_have_mapped_factory()
    {
        $this->assertFalse($this->sut->getTypeFactory('invalid_factory'));
    }
}
