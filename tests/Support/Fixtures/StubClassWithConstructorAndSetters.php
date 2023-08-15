<?php

namespace Tests\Support\Fixtures;

class StubClassWithConstructorAndSetters
{
    protected $valueOne;

    protected $valueTwo;

    protected $valueThree;

    protected $valueFour;

    protected $valueFive;

    public function __construct($valueOne, $valueTwo)
    {
        $this->valueOne = $valueOne;
        $this->valueTwo = $valueTwo;
    }

    public function setValueThree($valueThree)
    {
        $this->valueThree = $valueThree;
    }

    public function setValueFour($valueFour)
    {
        $this->valueFour = $valueFour;
    }

    public function setValueFive($valueFive)
    {
        $this->valueFive = $valueFive;
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
