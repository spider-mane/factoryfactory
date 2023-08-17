<?php

namespace WebTheory\Factory\Interfaces;

interface FixedFactoryRepositoryInterface
{
    public function getClassFactory(string $class): FixedFactoryInterface|false;
}
