<?php

namespace WebTheory\Factory\Repository;

use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\ClassResolverRepositoryInterface;

class EmptyClassResolverRepository implements ClassResolverRepositoryInterface
{
    public function getClassResolver(string $type): ClassResolverInterface|false
    {
        return false;
    }
}
