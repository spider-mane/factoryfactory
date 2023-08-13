<?php

namespace Tests\Support\Fixtures;

class StubClassWithFluentSetters
{
    protected $valueOne;

    protected $valueTwo;

    protected $valueThree;

    protected $valueFour;

    protected $valueFive;

    public function setValueOne($valueOne)
    {
        $this->valueOne = $valueOne;
    }

    public function valueTwo($valueTwo)
    {
        $this->valueTwo = $valueTwo;
    }

    public function valueThree($valueThree)
    {
        $this->valueThree = $valueThree;
    }

    public function valueFour($valueFour)
    {
        $this->valueFour = $valueFour;
    }

    public function valueFive($valueFive)
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
