<?php

namespace Tests\Suites\Unit\Transformation;

use PHPUnit\Framework\MockObject\MockObject;
use ReflectionParameter;
use stdClass;
use Tests\Support\Bases\PolicyDeferredTransformerTestCase;
use WebTheory\Factory\Exception\UnresolvableItemException;
use WebTheory\Factory\Exception\UnresolvableQueryException;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\ClassResolverRepositoryInterface;
use WebTheory\Factory\Transformation\ClassArgumentResolver;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

class ClassArgumentResolverTest extends PolicyDeferredTransformerTestCase
{
    use HasExpectedTypes;

    protected const REPOSITORY_QUERY_METHOD = 'getClassResolver';

    protected const RESOLVER_QUERY_METHOD = 'getClass';

    protected ClassArgumentResolver $sut;

    protected string $classKey = '@create';

    /**
     * @var MockObject&ClassResolverRepositoryInterface
     */
    protected ClassResolverRepositoryInterface $repository;

    /**
     * @var MockObject&ClassResolverInterface
     */
    protected ClassResolverInterface $resolver;

    /**
     * @var MockObject&ReflectionParameter
     */
    protected ReflectionParameter $parameter;

    protected function setUp(): void
    {
        parent::setUp();

        $mock = [$this, 'createMock'];

        $this->resolver = $mock(ClassResolverInterface::class);
        $this->parameter = $mock(ReflectionParameter::class);

        $this->sut = new ClassArgumentResolver(
            $this->repository = $mock(ClassResolverRepositoryInterface::class),
            $this->policy
        );
    }

    /**
     * @test
     */
    public function it_resolves_an_array_argument_into_a_class_argument_instance()
    {
        $argsToPass = $this->fakeAutoKeyedMap(10, 'word', 'streetName');
        $key = $this->unique->word();
        $classArg = $this->unique->word();
        $initialArgs = [$this->classKey => $classArg] + $argsToPass;
        $class = stdClass::class;

        $this->configurePolicy(true, $classArg, $argsToPass);

        $this->repository->method(static::REPOSITORY_QUERY_METHOD)
            ->with($key)
            ->willReturn($this->resolver);

        $this->resolver->method(static::RESOLVER_QUERY_METHOD)
            ->with($classArg)
            ->willReturn($class);

        /** @var ClassArgumentInterface */
        $result = $this->sut->transformArg($key, $initialArgs, $this->parameter);

        $this->assertInstanceOf(ClassArgumentInterface::class, $result);
        $this->assertSame($class, $result->getClass());
        $this->assertSame($argsToPass, $result->getArgs());
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_repository_cannot_resolve_resolver()
    {
        $this->configurePolicy(true);

        $this->repository->method(static::REPOSITORY_QUERY_METHOD)
            ->willReturn(false);

        $this->expectException(UnresolvableItemException::class);

        $this->sut->transformArg(
            $this->fake->slug(),
            [$this->classKey => $this->fake->word()],
            $this->parameter
        );
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_it_resolver_cannot_resolve_class()
    {
        $this->configurePolicy(true);

        $this->repository->method(static::REPOSITORY_QUERY_METHOD)
            ->willReturn($this->resolver);

        $this->resolver->method(static::RESOLVER_QUERY_METHOD)
            ->willReturn(false);

        $this->expectException(UnresolvableQueryException::class);

        $this->sut->transformArg(
            $this->fake->slug(),
            [$this->classKey => $this->fake->word()],
            $this->parameter
        );
    }
}
