<?php

namespace Tests\Support\Partials;

use Tests\Support\Concerns\SystemTrait;

trait HasExpectedTypes
{
    use SystemTrait;

    /**
     * @test
     * @dataProvider expectedTypesData
     */
    public function it_is_implementation_of_expected_types(string $type)
    {
        $this->assertInstanceOf($type, $this->sut);
    }

    public function expectedTypesData(): array
    {
        return $this->defineExpectedTypesData(
            fn (string $type) => $this->data('type', $type)->get()
        );
    }

    /**
     * @param callable(string $interface) $ds
     */
    abstract protected function defineExpectedTypesData(callable $ds): array;
}
