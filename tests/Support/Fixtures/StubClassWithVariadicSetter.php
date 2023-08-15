<?php

namespace Tests\Support\Fixtures;

class StubClassWithVariadicSetter
{
    protected array $value;

    public function setValue(...$value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
