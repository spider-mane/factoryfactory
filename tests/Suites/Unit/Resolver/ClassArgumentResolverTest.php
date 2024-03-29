<?php

namespace Tests\Suites\Unit\Resolver;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\Support\Fixtures\DummyClass;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Exception\UnresolvableEntryException;
use WebTheory\Factory\Exception\UnresolvableQueryException;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\ClassResolverRepositoryInterface;
use WebTheory\Factory\Interfaces\DependencyResolverInterface;
use WebTheory\Factory\Resolver\ClassArgumentResolver;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

class ClassArgumentResolverTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected const REPOSITORY_QUERY_METHOD = 'getClassResolver';

    protected const RESOLVER_QUERY_METHOD = 'getClass';

    protected ClassArgumentResolver $sut;

    /**
     * @var MockObject&ClassResolverRepositoryInterface
     */
    protected ClassResolverRepositoryInterface $repository;

    /**
     * @var MockObject&ClassResolverInterface
     */
    protected ClassResolverInterface $resolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = $this->createMock(ClassResolverInterface::class);

        $this->sut = new ClassArgumentResolver(
            $this->repository = $this->createMock(ClassResolverRepositoryInterface::class)
        );
    }

    protected function defineExpectedTypesData(callable $ds): array
    {
        return [
            $ds($t = DependencyResolverInterface::class) => [$t],
        ];
    }

    /**
     * @test
     */
    public function it_returns_a_class_argument_using_the_query_and_args()
    {
        $entry = $this->dummyArg();
        $query = $this->dummyArg();
        $args = $this->dummyArgs();
        $class = DummyClass::class;

        $this->repository->expects($this->once())
            ->method(static::REPOSITORY_QUERY_METHOD)
            ->with($entry)
            ->willReturn($this->resolver);

        $this->resolver->expects($this->once())
            ->method(static::RESOLVER_QUERY_METHOD)
            ->with($query)
            ->willReturn($class);

        /** @var ClassArgumentInterface */
        $result = $this->sut->resolve($entry, $query, $args);

        $this->assertInstanceOf(ClassArgumentInterface::class, $result);
        $this->assertSame($class, $result->getClass());
        $this->assertSame($args, $result->getArgs());
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_repository_cannot_resolve_resolver()
    {
        $this->repository->method(static::REPOSITORY_QUERY_METHOD)
            ->willReturn(false);

        $this->expectException(UnresolvableEntryException::class);

        $this->sut->resolve(
            $this->dummyArg(),
            $this->dummyArg(),
            $this->dummyArgs()
        );
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_it_resolver_cannot_resolve_class()
    {
        $this->repository->method(static::REPOSITORY_QUERY_METHOD)
            ->willReturn($this->resolver);

        $this->resolver->method(static::RESOLVER_QUERY_METHOD)
            ->willReturn(false);

        $this->expectException(UnresolvableQueryException::class);

        $this->sut->resolve(
            $this->dummyArg(),
            $this->dummyArg(),
            $this->dummyArgs()
        );
    }
}
