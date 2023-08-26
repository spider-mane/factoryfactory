<?php

namespace Tests\Suites\Unit\Resolver;

use PHPUnit\Framework\MockObject\MockObject;
use ReflectionParameter;
use Tests\Support\Bases\CompositeObjectResolverTestCase;
use WebTheory\Factory\Interfaces\ObjectResolverInterface;
use WebTheory\Factory\Resolver\CompositeObjectResolver;

class CompositeObjectResolverTest extends CompositeObjectResolverTestCase
{
    protected const SUT_METHOD = 'resolveObject';

    protected const COMPONENTS = 'resolvers';

    protected CompositeObjectResolver $sut;

    /**
     * @var list<MockObject&ObjectResolverInterface>
     */
    protected array $resolvers;

    /**
     * @var MockObject&ReflectionParameter
     */
    protected ReflectionParameter $reflectionParameter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reflectionParameter = $this->createMock(ReflectionParameter::class);
        $this->resolvers = $this->dummyList(
            fn () => $this->createMock(ObjectResolverInterface::class),
            5
        );

        $this->initExceptions();

        $this->sut = new CompositeObjectResolver(
            ...$this->resolvers
        );
    }

    protected function sutMethodArgs(): array
    {
        return [
            $this->dummyArg(),
            $this->dummyArg(),
            $this->dummyArgs(),
            $this->reflectionParameter,
        ];
    }
}
