<?php

namespace Tests\Support\Fixtures;

class StubClassWithVariadicArrayConstructor
{
    protected array $value;

    public function __construct(array ...$value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
