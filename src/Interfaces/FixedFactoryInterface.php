<?php

namespace WebTheory\Factory\Interfaces;

interface FixedFactoryInterface
{
    public function create(array $args = []): object;
}
