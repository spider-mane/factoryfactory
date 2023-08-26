<?php

namespace Tests\Suites\Unit\Repository;

use ArrayObject;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Support\Fixtures\DummyClass;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Interfaces\ClassArgumentGeneratorInterface;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;
use WebTheory\Factory\Repository\ArgumentiveFlexFactoryRepository;
use WebTheory\Factory\Repository\FlexFactoryRepository;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

class ArgumentiveFlexFactoryRepositoryTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected ArgumentiveFlexFactoryRepository $sut;

    /**
     * @var MockObject&ClassResolverInterface
     */
    protected ClassResolverInterface $resolver;

    /**
     * @var MockObject&FlexFactoryInterface
     */
    protected FlexFactoryInterface $factory;

    protected string $resolverKey;

    protected string $factoryKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resolverKey = $this->dummyArg();
        $this->factoryKey = $this->dummyArg();

        $this->resolver = $this->createMock(ClassResolverInterface::class);
        $this->factory = $this->createMock(FlexFactoryInterface::class);

        $this->sut = new ArgumentiveFlexFactoryRepository([
            $this->resolverKey => $this->resolver,
            $this->factoryKey => $this->factory,
        ]);
    }

    protected function defineExpectedTypesData(callable $ds): array
    {
        return [
            $ds($t = FlexFactoryRepositoryInterface::class) => [$t],
        ];
    }

    /**
     * @test
     */
    public function it_converts_class_resolver_to_class_argument_generator()
    {
        $class = DummyClass::class;

        $this->resolver->method('getClass')->willReturn($class);

        /** @var ClassArgumentGeneratorInterface */
        $result = $this->sut->getTypeFactory($this->resolverKey);
        $argument = $result->create('', []);

        $this->assertInstanceOf(ClassArgumentGeneratorInterface::class, $result);
        $this->assertSame($class, $argument->getClass());
    }

    /**
     * @test
     */
    public function it_returns_requested_factory()
    {
        $result = $this->sut->getTypeFactory($this->factoryKey);

        $this->assertSame($this->factory, $result);
    }

    /**
     * @test
     */
    public function it_returns_false_if_it_does_not_have_mapped_factory()
    {
        $this->assertFalse($this->sut->getTypeFactory('invalid_factory'));
    }

    /**
     * @test
     */
    public function it_accepts_array_accessible_object_as_constructor_argument()
    {
        $accessible = new ArrayObject([]);

        $result = new FlexFactoryRepository($accessible);

        $this->assertInstanceOf(FlexFactoryRepository::class, $result);
    }
}
