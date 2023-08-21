<?php

namespace Tests\Suites\Unit\Factory;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Support\Fixtures\DummyClass;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Factory\ClassArgumentGenerator;
use WebTheory\Factory\Interfaces\ClassArgumentGeneratorInterface;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

class ClassArgumentGeneratorFactoryTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected const RESOLVER_RESOLUTION_METHOD = 'getClass';

    protected ClassArgumentGenerator $sut;

    /**
     * @var MockObject&ClassResolverInterface
     */
    protected ClassResolverInterface $resolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new ClassArgumentGenerator(
            $this->resolver = $this->createMock(ClassResolverInterface::class)
        );
    }

    protected function defineExpectedTypesData(callable $ds): array
    {
        return [
            $ds($i = ClassArgumentGeneratorInterface::class) => [$i],
        ];
    }

    /**
     * @test
     */
    public function it_creates_class_argument_from_resolved_class()
    {
        $class = DummyClass::class;
        $query = $this->dummyArg();
        $args = $this->fakeAutoKeyedMap(6, 'word');

        $this->resolver->expects($this->once())
            ->method(static::RESOLVER_RESOLUTION_METHOD)
            ->with($query)
            ->willReturn($class);

        /** @var ClassArgumentInterface */
        $result = $this->sut->create($query, $args);

        $this->assertSame($class, $result->getClass());
        $this->assertSame($args, $result->getArgs());
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_class_query_cannot_be_resolved()
    {
        $this->resolver->method(static::RESOLVER_RESOLUTION_METHOD)
            ->willReturn(false);

        $this->expectException(InvalidArgumentException::class);

        $this->sut->create($this->dummyArg(), []);
    }
}
