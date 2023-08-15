<?php

namespace WebTheory\Factory\Repository;

use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;

class EmptyFlexFactoryRepository implements FlexFactoryRepositoryInterface
{
    public function getTypeFactory(string $type): FlexFactoryInterface|false
    {
        return false;
    }
}
