<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\DependencyResolverInterface;
use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;
use WebTheory\Factory\Resolver\Abstracts\DependencyResolverTrait;

class FlexFactoryRouter implements DependencyResolverInterface
{
    use DependencyResolverTrait;

    public function __construct(protected FlexFactoryRepositoryInterface $repository)
    {
        //
    }

    public function resolve(string $item, string $query, array $args): object
    {
        return $this->getHandler($item)->create($query, $args);
    }

    protected function getHandler(string $item): FlexFactoryInterface
    {
        return $this->repository->getTypeFactory($item)
            ?: throw $this->unresolvableItemException($item);
    }
}
