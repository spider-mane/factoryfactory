<?php

namespace Tests\Suites\Unit\Transformation;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionParameter;
use stdClass;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\ClassResolverRepositoryInterface;
use WebTheory\Factory\Transformation\ClassArgumentResolver;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

class ClassArgumentResolverTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected const REPOSITORY_QUERY_METHOD = 'getClassResolver';

    protected const RESOLVER_QUERY_METHOD = 'getClass';

    protected ClassArgumentResolver $sut;

    protected string $classKey = ClassArgumentResolver::DEFAULT_CLASS_KEY;

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
            $this->classKey
        );
    }

    protected function defineExpectedTypesData(callable $ds): array
    {
        return [
            $ds($i = ArgValueTransformerInterface::class) => [$i],
        ];
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

        $this->repository->method(static::REPOSITORY_QUERY_METHOD)
            ->with($key)
            ->willReturn($this->resolver);

        $this->resolver->method(static::RESOLVER_QUERY_METHOD)
            ->with($classArg)
            ->willReturn($class);

        $result = $this->sut->transformArg($key, $initialArgs, $this->parameter);

        $this->assertInstanceOf(ClassArgumentInterface::class, $result);
        $this->assertSame($class, $result->getClass());
        $this->assertSame($argsToPass, $result->getArgs());
    }

    /**
     * @test
     * @dataProvider originalValueData
     */
    public function it_returns_the_original_value_if_does_not_meet_specifications(mixed $value)
    {
        $result = $this->sut->transformArg(
            $this->fake->word(),
            $value,
            $this->parameter
        );

        $this->assertSame($value, $result);
    }

    public function originalValueData(): array
    {
        $this->init();

        $ds = fn (string $condition) => $this->ds()
            ->set('condition', $condition)
            ->get();

        return [
            $ds('not an array') => [$this->fake->address()],
            $ds('no creation key') => [$this->fakeAutoKeyedMap(10, 'address')],
        ];
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_repository_cannot_resolve_resolver()
    {
        $this->repository->method(static::REPOSITORY_QUERY_METHOD)
            ->willReturn(false);

        $this->expectException(InvalidArgumentException::class);

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
        $this->repository->method(static::REPOSITORY_QUERY_METHOD)
            ->willReturn($this->resolver);

        $this->resolver->method(static::RESOLVER_QUERY_METHOD)
            ->willReturn(false);

        $this->expectException(InvalidArgumentException::class);

        $this->sut->transformArg(
            $this->fake->slug(),
            [$this->classKey => $this->fake->word()],
            $this->parameter
        );
    }
}
