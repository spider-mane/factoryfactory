<?php

namespace WebTheory\Factory\Repository;

use ArrayAccess;
use WebTheory\Factory\Interfaces\FixedFactoryInterface;
use WebTheory\Factory\Interfaces\FixedFactoryRepositoryInterface;

class FixedFactoryRepository implements FixedFactoryRepositoryInterface
{
    /**
     * @param array<string, FixedFactoryInterface> $factories
     */
    public function __construct(protected array|ArrayAccess $factories = [])
    {
        //
    }

    public function getClassFactory(string $type): FixedFactoryInterface|false
    {
        return $this->factories[$type] ?? false;
    }
}
