<?php

namespace WebTheory\Factory\Repository;

use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;

class FlexFactoryRepository implements FlexFactoryRepositoryInterface
{
    /**
     * @param array<string, FlexFactoryInterface> $factories
     */
    public function __construct(protected array $factories = [])
    {
        //
    }

    public function getTypeFactory(string $type): FlexFactoryInterface|false
    {
        return $this->factories[$type] ?? false;
    }
}
