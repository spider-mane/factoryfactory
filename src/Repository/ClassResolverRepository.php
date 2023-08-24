<?php

namespace WebTheory\Factory\Repository;

use ArrayAccess;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\ClassResolverRepositoryInterface;

class ClassResolverRepository implements ClassResolverRepositoryInterface
{
    /**
     * @param array<string, ClassResolverInterface> $resolvers
     */
    public function __construct(protected array|ArrayAccess $resolvers = [])
    {
        //
    }

    public function getClassResolver(string $type): ClassResolverInterface|false
    {
        return $this->resolvers[$type] ?? false;
    }
}
