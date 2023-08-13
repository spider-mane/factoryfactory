<?php

namespace WebTheory\Factory\Interfaces;

interface FactoryInterface
{
    public function create(string $class, array $args = []): object;
}
