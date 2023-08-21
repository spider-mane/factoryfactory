<?php

namespace Tests\Support\Bases;

use PHPUnit\Framework\MockObject\MockObject;
use ReflectionParameter;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;
use WebTheory\Factory\Interfaces\CreationArgumentPolicyInterface;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

abstract class PolicyDeferredTransformerTestCase extends UnitTestCase
{
    use HasExpectedTypes;

    protected const POLICY_VALIDATION_METHOD = 'isCreationData';

    protected const POLICY_EXTRACTION_METHOD = 'extractCreationQuery';

    protected const POLICY_NORMALIZATION_METHOD = 'normalizeArgs';

    /**
     * @var MockObject&CreationArgumentPolicyInterface
     */
    protected CreationArgumentPolicyInterface $policy;

    /**
     * @var MockObject&ReflectionParameter
     */
    protected ReflectionParameter $parameter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = $this->createMock(CreationArgumentPolicyInterface::class);
        $this->parameter = $this->createMock(ReflectionParameter::class);
    }

    protected function configurePolicy(bool $isValid, string $query = '', array $args = []): void
    {
        $this->policy->method(static::POLICY_VALIDATION_METHOD)
            ->willReturn($isValid);

        $this->policy->method(static::POLICY_EXTRACTION_METHOD)
            ->willReturn($query);

        $this->policy->method(static::POLICY_NORMALIZATION_METHOD)
            ->willReturn($args);
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
    public function it_returns_the_original_value_if_does_not_meet_specifications()
    {
        $value = $this->fake->address();

        $result = $this->getSut()->transformArg(
            $this->fake->word(),
            $value,
            $this->parameter
        );

        $this->configurePolicy(false);

        $this->assertSame($value, $result);
    }
}
