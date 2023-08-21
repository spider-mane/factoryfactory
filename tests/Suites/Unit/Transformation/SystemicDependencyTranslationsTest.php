<?php

namespace Tests\Suites\Unit\Transformation;

use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use Tests\Support\Bases\PolicyDeferredTransformerTestCase;
use Tests\Support\Fixtures\DummyClass;
use WebTheory\Factory\Interfaces\UniversalDependencyResolverInterface;
use WebTheory\Factory\Transformation\SystemicDependencyTranslations;

class SystemicDependencyTranslationsTest extends PolicyDeferredTransformerTestCase
{
    protected const RESOLVER_RESOLUTION_METHOD = 'resolve';

    protected const REFLECTION_CLASS_GET_NAME_METHOD = 'getName';

    protected const PARAMETER_GET_CLASS_METHOD = 'getDeclaringClass';

    protected SystemicDependencyTranslations $sut;

    /**
     * @var MockObject&UniversalDependencyResolverInterface
     */
    protected UniversalDependencyResolverInterface $resolver;

    /**
     * @var MockObject&ReflectionClass
     */
    protected ReflectionClass $reflectionClass;

    protected function setUp(): void
    {
        parent::setUp();

        $mock = [$this, 'createMock'];

        $this->reflectionClass = $mock(ReflectionClass::class);

        $this->sut = new SystemicDependencyTranslations(
            $this->resolver = $mock(UniversalDependencyResolverInterface::class),
            $this->policy
        );
    }

    /**
     * @test
     */
    public function it_returns_object_created_by_resolver()
    {
        $item = $this->fake->word();
        $query = $this->fake->word();
        $args = $this->fakeAutoKeyedMap(7, 'sentence');
        $initial = [$item => $query] + $args;
        $class = DummyClass::class;
        $expected = new $class();

        $this->configurePolicy(true, $query, $args);

        $this->reflectionClass->method(static::REFLECTION_CLASS_GET_NAME_METHOD)
            ->willReturn($class);

        $this->parameter->method(static::PARAMETER_GET_CLASS_METHOD)
            ->willReturn($this->reflectionClass);

        # Expect
        $this->resolver->expects($this->once())
            ->method(static::RESOLVER_RESOLUTION_METHOD)
            ->with($class, $item, $query, $args)
            ->willReturn($expected);

        # Act
        $result = $this->sut->transformArg($item, $initial, $this->parameter);

        # Assert
        $this->assertSame($expected, $result);
    }
}
