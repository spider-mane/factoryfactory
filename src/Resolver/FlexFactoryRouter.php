<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Exception\UnresolvableItemException;
use WebTheory\Factory\Interfaces\DependencyResolverInterface;
use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;

class FlexFactoryRouter implements DependencyResolverInterface
{
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
            ?: throw new UnresolvableItemException($item);
    }
}
