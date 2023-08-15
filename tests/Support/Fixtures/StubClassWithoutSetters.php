<?php

namespace Tests\Support\Fixtures;

class StubClassWithoutSetters
{
    public function __construct(
        protected $valueOne,
        protected $valueTwo = null,
        protected $valueThree = null,
        protected $valueFour = null,
        protected $valueFive = null
    ) {
        //
    }

    public function getValueOne()
    {
        return $this->valueOne;
    }

    public function getValueTwo()
    {
        return $this->valueTwo;
    }

    public function getValueThree()
    {
        return $this->valueThree;
    }

    public function getValueFour()
    {
        return $this->valueFour;
    }

    public function getValueFive()
    {
        return $this->valueFive;
    }
}
