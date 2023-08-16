<?php

namespace Tests\Suites\Unit\Transformation;

use PHPUnit\Framework\MockObject\MockObject;
use ReflectionParameter;
use Tests\Support\Partials\HasExpectedTypes;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;
use WebTheory\Factory\Transformation\CompositeArgValueTransformer;

class CompositeArgValueTransformerTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected const TRANSFORMATION_METHOD = 'transformArg';

    protected CompositeArgValueTransformer $sut;

    /**
     * @var list<MockObject&ArgValueTransformerInterface>
     */
    protected array $transformers;

    /**
     * @var MockObject&ReflectionParameter
     */
    protected ReflectionParameter $parameter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parameter = $this->createMock(ReflectionParameter::class);

        $this->sut = new CompositeArgValueTransformer(
            ...$this->transformers = $this->createTransformerList(2)
        );
    }

    /**
     * @return list<MockObject&ArgValueTransformerInterface>
     */
    protected function createTransformerList(int $count): array
    {
        return $this->dummyList(
            fn () => $this->createMock(ArgValueTransformerInterface::class),
            $count
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
    public function it_returns_product_of_sequential_transformer_calls()
    {
        $method = self::TRANSFORMATION_METHOD;
        $key = $this->dummyArg();
        $param = $this->parameter;

        $original = $this->fake->name();
        $transformation1 = $this->fake->company();
        $transformation2 = $this->fake->address();

        $expected = $transformation2;

        # Expect
        $this->transformers[0]->method($method)
            ->with($key, $original, $param)
            ->willReturn($transformation1);

        $this->transformers[1]->method($method)
            ->with($key, $transformation1, $param)
            ->willReturn($transformation2);

        # Act
        $result = $this->sut->transformArg($key, $original, $param);

        # Assert
        $this->assertSame($expected, $result);
    }
}
