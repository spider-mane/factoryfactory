<?php

namespace Tests\Support\Fixtures;

class StubClassWithoutSetters
{
    public function __construct(
        protected $valueOne,
        protected $valueTwo,
        protected $valueThree,
        protected $valueFour,
        protected $valueFive,
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
