<?php

namespace Tests\Suites\Unit\Resolver;

use PHPUnit\Framework\MockObject\MockObject;
use stdClass;
use Tests\Support\Fixtures\DummyClass;
use Tests\Support\Fixtures\Namespaces\DummyNamespace1\DummyClass1;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Exception\UnresolvableEntryException;
use WebTheory\Factory\Exception\UnresolvableQueryException;
use WebTheory\Factory\Exception\UnresolvableSubjectException;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\ClassResolverRepositoryInterface;
use WebTheory\Factory\Interfaces\UniversalDependencyResolverInterface;
use WebTheory\Factory\Resolver\ClassResolverTree;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

class ClassResolverTreeTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected const REPOSITORY_GET_RESOLVER_METHOD = 'getClassResolver';

    protected const RESOLVER_RESOLUTION_METHOD = 'getClass';

    protected ClassResolverTree $sut;

    /**
     * @var MockObject&ClassResolverRepositoryInterface
     */
    protected ClassResolverRepositoryInterface $repository;

    /**
     * @var MockObject&ClassResolverInterface
     */
    protected ClassResolverInterface $resolver;

    protected $subject = DummyClass::class;

    protected function setUp(): void
    {
        parent::setUp();

        $mock = [$this, 'createMock'];

        $this->resolver = $mock(ClassResolverInterface::class);
        $this->repository = $mock(ClassResolverRepositoryInterface::class);

        $this->sut = new ClassResolverTree([
            $this->subject => $this->repository,
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
    public function it_returns_a_class_argument_instance_of_resolved_class_and_passed_args()
    {
        $entry = $this->dummyArg();
        $query = $this->dummyArg();
        $args = $this->fakeAutoKeyedMap(5, 'sentence');
        $expected = DummyClass1::class;

        $this->repository->expects($this->once())
            ->method(static::REPOSITORY_GET_RESOLVER_METHOD)
            ->with($entry)
            ->willReturn($this->resolver);

        $this->resolver->expects($this->once())
            ->method(static::RESOLVER_RESOLUTION_METHOD)
            ->with($query)
            ->willReturn($expected);

        /** @var ClassArgumentInterface */
        $result = $this->sut->resolve($this->subject, $entry, $query, $args);

        $this->assertSame($expected, $result->getClass());
        $this->assertSame($args, $result->getArgs());
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_class_argument_is_not_mapped_to_a_repository()
    {
        $this->expectException(UnresolvableSubjectException::class);

        $this->sut->resolve(
            stdClass::class,
            $this->dummyArg(),
            $this->dummyArg(),
            []
        );
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_entry_argument_is_not_mapped_to_a_resolver()
    {
        $this->repository->method(static::REPOSITORY_GET_RESOLVER_METHOD)
            ->willReturn(false);

        $this->expectException(UnresolvableEntryException::class);

        $this->sut->resolve(
            $this->subject,
            $this->dummyArg(),
            $this->dummyArg(),
            []
        );
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_class_query_cannot_be_resolved()
    {
        $this->repository->method(static::REPOSITORY_GET_RESOLVER_METHOD)
            ->willReturn($this->resolver);

        $this->resolver->method(static::RESOLVER_RESOLUTION_METHOD)
            ->willReturn(false);

        $this->expectException(UnresolvableQueryException::class);

        $this->sut->resolve(
            $this->subject,
            $this->dummyArg(),
            $this->dummyArg(),
            []
        );
    }
}
