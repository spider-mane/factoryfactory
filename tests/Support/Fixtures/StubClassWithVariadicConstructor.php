<?php

namespace Tests\Support\Fixtures;

class StubClassWithVariadicConstructor
{
    protected array $value;

    public function __construct(...$value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
