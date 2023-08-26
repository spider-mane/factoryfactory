<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Exception\UnresolvableEntryException;
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

    public function resolve(string $entry, string $query, array $args): ClassArgumentInterface
    {
        return new ClassArgument($this->getClass($entry, $query), $args);
    }

    protected function getClass(string $entry, string $query): string
    {
        return $this->getResolver($entry)->getClass($query)
            ?: throw new UnresolvableQueryException($query);
    }

    protected function getResolver(string $entry): ClassResolverInterface
    {
        return $this->repository->getClassResolver($entry)
            ?: throw new UnresolvableEntryException($entry);
    }
}
