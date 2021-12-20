<?php

namespace Tests\Support\Dummies;

use DateTime;

class DummyFactory
{
    protected $valueOne;
    protected $valueTwo;
    protected $valueThree;
    protected $valueFour;
    protected $valueFive;

    public function __construct($valueOne, $valueTwo)
    {
        $this->valueOne = $valueOne;
        $this->setValueTwo($valueTwo);
    }

    public function setValueTwo($valueTwo)
    {
        $this->valueTwo = $valueTwo;
    }

    public function setValueThree($valueThree)
    {
        $this->valueThree = $valueThree;
    }

    public function setValueFour(DateTime ...$valueFour)
    {
        $this->valueFour = $valueFour;
    }

    public function withValueFive($valueFive)
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
