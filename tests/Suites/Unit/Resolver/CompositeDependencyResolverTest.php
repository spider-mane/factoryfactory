<?php

namespace Tests\Suites\Unit\Resolver;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\Support\Bases\CompositeObjectResolverTestCase;
use WebTheory\Factory\Interfaces\DependencyResolverInterface;
use WebTheory\Factory\Resolver\CompositeDependencyResolver;

class CompositeDependencyResolverTest extends CompositeObjectResolverTestCase
{
    protected const SUT_METHOD = 'resolve';

    protected const COMPONENTS = 'resolvers';

    protected CompositeDependencyResolver $sut;

    /**
     * @var list<MockObject&DependencyResolverInterface>
     */
    protected array $resolvers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resolvers = $this->dummyList(
            fn () => $this->createMock(DependencyResolverInterface::class),
            5
        );

        $this->initExceptions();

        $this->sut = new CompositeDependencyResolver(
            ...$this->resolvers
        );
    }

    protected function sutMethodArgs(): array
    {
        return [
            $this->dummyArg(),
            $this->dummyArg(),
            $this->dummyArgs(),
        ];
    }
}
