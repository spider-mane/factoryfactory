<?php

namespace WebTheory\Factory\Interfaces;

interface FlexFactoryCoreInterface
{
    public function process(string $query, array $args = []): object|false;
}
