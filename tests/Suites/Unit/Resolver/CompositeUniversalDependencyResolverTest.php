<?php

namespace Tests\Suites\Unit\Resolver;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\Support\Bases\CompositeObjectResolverTestCase;
use Tests\Support\Fixtures\DummyClass;
use WebTheory\Factory\Interfaces\ArgTransformationExceptionInterface;
use WebTheory\Factory\Interfaces\UniversalDependencyResolverInterface;
use WebTheory\Factory\Resolver\CompositeUniversalDependencyResolver;

class CompositeUniversalDependencyResolverTest extends CompositeObjectResolverTestCase
{
    protected const SUT_METHOD = 'resolve';

    protected const COMPONENTS = 'resolvers';

    protected CompositeUniversalDependencyResolver $sut;

    /**
     * @var list<MockObject&UniversalDependencyResolverInterface>
     */
    protected array $resolvers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resolvers = $this->dummyList(
            fn () => $this->createMock(UniversalDependencyResolverInterface::class),
            5
        );

        $this->initExceptions();

        $this->sut = new CompositeUniversalDependencyResolver(
            ...$this->resolvers
        );
    }

    protected function sutMethodArgs(): array
    {
        return [
            DummyClass::class,
            $this->dummyArg(),
            $this->dummyArg(),
            $this->dummyArgs(),
        ];
    }
}
