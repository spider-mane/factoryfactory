<?php

namespace WebTheory\Factory\Interfaces;

interface FactoryCoreInterface
{
    public function produce(string $class, array $args = []): object|false;
}
