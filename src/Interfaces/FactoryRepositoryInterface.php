<?php

namespace WebTheory\Factory\Interfaces;

interface FactoryRepositoryInterface
{
    public function getFactoryFor(string $interface): FactoryInterface;
}
