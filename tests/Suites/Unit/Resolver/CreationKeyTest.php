<?php

namespace Tests\Suites\Unit\Resolver;

use Tests\Support\UnitTestCase;
use WebTheory\Factory\Interfaces\CreationArgumentPolicyInterface;
use WebTheory\Factory\Resolver\CreationKey;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

class CreationKeyTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected CreationKey $sut;

    protected string $key = '@create';

    protected array $args;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new CreationKey($this->key);
    }

    protected function init(): void
    {
        parent::init();

        $this->args = $this->fakeAutoKeyedMap(4, 'colorName');
    }

    protected function defineExpectedTypesData(callable $ds): array
    {
        return [
            $ds($i = CreationArgumentPolicyInterface::class) => [$i],
        ];
    }

    /**
     * @test
     * @dataProvider argumentValidationData
     */
    public function it_correctly_determines_whether_or_not_argument_is_creation_data(bool $isValid, array $args)
    {
        $result = $this->sut->isCreationData($args);

        $isValid ? $this->assertTrue($result) : $this->assertFalse($result);
    }

    public function argumentValidationData(): array
    {
        $this->init();

        $ds = fn (bool $isValid) => $this->dataSet('valid', $isValid);

        return [
            $ds(true) => [
                'valid' => true,
                'args' => [$this->key => $this->dummyArg()] + $this->args,
            ],

            $ds(false) => [
                'valid' => false,
                'args' => $this->args,
            ],
        ];
    }

    /**
     * @test
     */
    public function it_extracts_the_value_of_the_creation_key()
    {
        $expected = $this->dummyArg();
        $args = [$this->key => $expected] + $this->args;

        $result = $this->sut->extractCreationQuery($args);

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function it_removes_creation_key_from_args()
    {
        $args = [$this->key => $this->dummyArg()] + $this->args;

        $result = $this->sut->normalizeArgs($args);

        $this->assertSame($this->args, $result);
    }
}
