<?php

namespace Tests\Support\Fixtures;

class StubClassWithMixedVariadicConstructor
{
    protected mixed $valueOne;

    protected array $valueTwo;

    public function __construct($valueOne, ...$valueTwo)
    {
        $this->valueOne = $valueOne;
        $this->valueTwo = $valueTwo;
    }

    public function getValueOne()
    {
        return $this->valueOne;
    }

    public function getValueTwo()
    {
        return $this->valueTwo;
    }
}
