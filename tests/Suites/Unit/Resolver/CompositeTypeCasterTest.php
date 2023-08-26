<?php

namespace Tests\Suites\Unit\Resolver;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\Support\Bases\CompositeObjectResolverTestCase;
use Tests\Support\Fixtures\DummyClass;
use WebTheory\Factory\Interfaces\TypeCasterInterface;
use WebTheory\Factory\Resolver\CompositeTypeCaster;

class CompositeTypeCasterTest extends CompositeObjectResolverTestCase
{
    protected const SUT_METHOD = 'cast';

    protected const COMPONENTS = 'casters';

    protected CompositeTypeCaster $sut;

    /**
     * @var list<MockObject&TypeCasterInterface>
     */
    protected array $casters;

    protected function setUp(): void
    {
        parent::setUp();

        $this->casters = $this->dummyList(
            fn () => $this->createMock(TypeCasterInterface::class),
            5
        );

        $this->initExceptions();

        $this->sut = new CompositeTypeCaster(
            ...$this->casters
        );
    }

    protected function sutMethodArgs(): array
    {
        return [
            DummyClass::class,
            $this->dummyArg(),
            $this->dummyArgs(),
        ];
    }
}
