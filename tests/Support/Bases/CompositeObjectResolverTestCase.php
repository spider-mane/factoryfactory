<?php

namespace Tests\Support\Bases;

use Exception;
use LogicException;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Support\Fixtures\DummyClass;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Interfaces\ArgTransformationExceptionInterface;

abstract class CompositeObjectResolverTestCase extends UnitTestCase
{
    protected const SUT_METHOD = '';

    protected const COMPONENTS = '';

    /**
     * @var list<ArgTransformationExceptionInterface&Exception>
     */
    protected array $exceptions;

    abstract protected function sutMethodArgs(): array;

    /**
     * @return list<MockObject>
     */
    protected function getComposition(): array
    {
        return $this->{static::COMPONENTS};
    }

    protected function initExceptions(): void
    {
        $this->exceptions = $this->dummyList(
            fn () => $this->createMock(ArgTransformationExceptionInterface::class),
            count($this->getComposition())
        );
    }

    protected function errorize(iterable $components): void
    {
        foreach ($components as $i => $component) {
            $component->method(static::SUT_METHOD)
                ->willThrowException($this->exceptions[$i]);
        }
    }

    /**
     * @test
     */
    public function it_returns_the_value_returned_by_the_first_component_that_does_not_throw_an_argument_resolution_exception()
    {
        $method = static::SUT_METHOD;
        $expected = new DummyClass();
        $components = $this->getComposition();

        array_pop($components)->method($method)
            ->willThrowException($this->exceptions[0]);

        array_pop($components)->method($method)->willReturn($expected);

        $this->errorize($components);

        $result = $this->performSystemAction(
            $this->getSut(),
            $method,
            $this->sutMethodArgs()
        );

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function it_throws_the_last_thrown_argument_resolution_exception_upon_complete_failure()
    {
        $exceptions = $this->exceptions;

        $this->errorize($this->getComposition());

        $this->expectExceptionObject(end($exceptions));

        $this->performSystemAction(
            $this->getSut(),
            static::SUT_METHOD,
            $this->sutMethodArgs()
        );
    }

    /**
     * @test
     */
    public function it_immediately_throws_the_first_non_argument_resolution_exception()
    {
        $method = static::SUT_METHOD;
        $components = $this->getComposition();
        $expected = new LogicException();

        array_shift($components)->method($method)
            ->willThrowException($this->exceptions[0]);

        array_shift($components)->method($method)
            ->willThrowException($expected);

        foreach ($components as $component) {
            $component->expects($this->never())->method($method);
        }

        $this->expectExceptionObject($expected);

        $this->performSystemAction(
            $this->getSut(),
            $method,
            $this->sutMethodArgs()
        );
    }
}
