<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Exception\UnresolvableItemException;
use WebTheory\Factory\Exception\UnresolvableQueryException;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\ClassResolverRepositoryInterface;
use WebTheory\Factory\Interfaces\DependencyResolverInterface;
use WebTheory\Factory\Transformation\ClassArgument;

class ClassArgumentResolver implements DependencyResolverInterface
{
    public function __construct(protected ClassResolverRepositoryInterface $repository)
    {
        //
    }

    public function resolve(string $item, string $query, array $args): ClassArgumentInterface
    {
        return new ClassArgument($this->getClass($item, $query), $args);
    }

    protected function getClass(string $item, string $query): string
    {
        return $this->getResolver($item)->getClass($query)
            ?: throw new UnresolvableQueryException($query);
    }

    protected function getResolver(string $item): ClassResolverInterface
    {
        return $this->repository->getClassResolver($item)
            ?: throw new UnresolvableItemException($item);
    }
}
