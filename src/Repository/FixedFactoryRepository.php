<?php

namespace WebTheory\Factory\Repository;

use WebTheory\Factory\Interfaces\FixedFactoryInterface;
use WebTheory\Factory\Interfaces\FixedFactoryRepositoryInterface;

class FixedFactoryRepository implements FixedFactoryRepositoryInterface
{
    /**
     * @param array<string, FixedFactoryInterface> $factories
     */
    public function __construct(protected array $factories = [])
    {
        //
    }

    public function getClassFactory(string $type): FixedFactoryInterface|false
    {
        return $this->factories[$type] ?? false;
    }
}
