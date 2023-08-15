<?php

namespace WebTheory\Factory\Interfaces;

interface FlexFactoryInterface
{
    public function create(string $class, array $args = []): object;
}
