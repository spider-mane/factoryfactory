<?php

namespace WebTheory\Factory\Transformation;

use WebTheory\Factory\Interfaces\ClassArgumentInterface;

class ClassArgument implements ClassArgumentInterface
{
    public function __construct(protected string $class, protected array $args = [])
    {
        //
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
