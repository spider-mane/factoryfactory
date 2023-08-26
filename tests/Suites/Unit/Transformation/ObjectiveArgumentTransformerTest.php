<?php

namespace Tests\Suites\Unit\Transformation;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\Support\Bases\PolicyDeferredTransformerTestCase;
use Tests\Support\Fixtures\DummyClass;
use WebTheory\Factory\Interfaces\ObjectResolverInterface;
use WebTheory\Factory\Transformation\ObjectiveArgumentTransformer;

class ObjectiveArgumentTransformerTest extends PolicyDeferredTransformerTestCase
{
    protected const RESOLVER_RESOLUTION_METHOD = 'resolveObject';

    protected ObjectiveArgumentTransformer $sut;

    /**
     * @var MockObject&ObjectResolverInterface
     */
    protected ObjectResolverInterface $resolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new ObjectiveArgumentTransformer(
            $this->policy,
            $this->resolver = $this->createMock(ObjectResolverInterface::class)
        );
    }

    /**
     * @test
     */
    public function it_returns_object_created_by_resolver()
    {
        $param = $this->parameter;
        $item = $this->fake->word();
        $query = $this->fake->word();
        $normalizedArgs = $this->fakeAutoKeyedMap(7, 'sentence');
        $initialArgs = [$item => $query] + $normalizedArgs;
        $expected = new DummyClass();

        $this->configurePolicy(true, $query, $normalizedArgs);

        # Expect
        $this->resolver->expects($this->once())
            ->method(static::RESOLVER_RESOLUTION_METHOD)
            ->with($item, $query, $normalizedArgs, $param)
            ->willReturn($expected);

        # Act
        $result = $this->sut->transformArg($item, $initialArgs, $param);

        # Assert
        $this->assertSame($expected, $result);
    }
}
