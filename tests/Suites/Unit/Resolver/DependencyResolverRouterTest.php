<?php

namespace Tests\Suites\Unit\Resolver;

use PHPUnit\Framework\MockObject\MockObject;
use stdClass;
use Tests\Support\Fixtures\DummyClass;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Exception\UnresolvableSubjectException;
use WebTheory\Factory\Interfaces\DependencyResolverInterface;
use WebTheory\Factory\Interfaces\UniversalDependencyResolverInterface;
use WebTheory\Factory\Resolver\DependencyResolverRouter;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

class DependencyResolverRouterTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected const RESOLVER_RESOLUTION_METHOD = 'resolve';

    protected DependencyResolverRouter $sut;

    /**
     * @var MockObject&DependencyResolverInterface
     */
    protected DependencyResolverInterface $resolver;

    protected string $class = DummyClass::class;

    protected string $item;

    protected string $query;

    protected array $args;

    protected function setUp(): void
    {
        parent::setUp();

        $mock = [$this, 'createMock'];

        $this->resolver = $mock(DependencyResolverInterface::class);

        $this->item = $this->dummyArg();
        $this->query = $this->dummyArg();
        $this->args = $this->fakeAutoKeyedMap(5, 'sentence');

        $this->sut = new DependencyResolverRouter([
            $this->class => $this->resolver,
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
    public function it_returns_a_object_created_nested_resolver()
    {
        $class = $this->class;
        $item = $this->item;
        $query = $this->query;
        $args = $this->fakeAutoKeyedMap(5, 'sentence');
        $expected = new DummyClass();

        $this->resolver->expects($this->once())
            ->method(static::RESOLVER_RESOLUTION_METHOD)
            ->with($item)
            ->willReturn($expected);

        $result = $this->sut->resolve($class, $item, $query, $args);

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_class_is_not_mapped_to_a_resolver()
    {
        $this->expectException(UnresolvableSubjectException::class);

        $this->sut->resolve(stdClass::class, $this->item, $this->query, []);
    }
}
