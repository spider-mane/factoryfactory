<?php

namespace WebTheory\Factory\Repository;

use WebTheory\Factory\Interfaces\FixedFactoryInterface;
use WebTheory\Factory\Interfaces\FixedFactoryRepositoryInterface;

class EmptyFixedFactoryRepository implements FixedFactoryRepositoryInterface
{
    public function getClassFactory(string $type): FixedFactoryInterface|false
    {
        return false;
    }
}
