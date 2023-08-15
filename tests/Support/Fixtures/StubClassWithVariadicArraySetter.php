<?php

namespace Tests\Support\Fixtures;

class StubClassWithVariadicArraySetter
{
    protected array $value;

    public function setValue(array ...$value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
