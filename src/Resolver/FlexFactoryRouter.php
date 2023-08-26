<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Exception\UnresolvableEntryException;
use WebTheory\Factory\Interfaces\DependencyResolverInterface;
use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;

class FlexFactoryRouter implements DependencyResolverInterface
{
    public function __construct(protected FlexFactoryRepositoryInterface $repository)
    {
        //
    }

    public function resolve(string $entry, string $query, array $args): object
    {
        return $this->getHandler($entry)->create($query, $args);
    }

    protected function getHandler(string $entry): FlexFactoryInterface
    {
        return $this->repository->getTypeFactory($entry)
            ?: throw new UnresolvableEntryException($entry);
    }
}
