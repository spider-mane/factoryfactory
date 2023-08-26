<?php

namespace WebTheory\Factory\Resolver;

use ArrayAccess;
use WebTheory\Factory\Exception\UnresolvableItemException;
use WebTheory\Factory\Exception\UnresolvableQueryException;
use WebTheory\Factory\Exception\UnresolvableSubjectException;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\ClassResolverRepositoryInterface;
use WebTheory\Factory\Interfaces\UniversalDependencyResolverInterface;
use WebTheory\Factory\Transformation\ClassArgument;

class ClassResolverTree implements UniversalDependencyResolverInterface
{
    /**
     * @param array<class-string, ClassResolverRepositoryInterface> $repositories
     */
    public function __construct(protected array|ArrayAccess $repositories)
    {
        //
    }

    public function resolve(string $for, string $item, string $query, array $args): ClassArgumentInterface
    {
        return new ClassArgument($this->getClass($for, $item, $query), $args);
    }

    protected function getClass(string $for, string $item, string $query): string
    {
        return $this->getResolver($for, $item)->getClass($query)
            ?: throw new UnresolvableQueryException($query);
    }

    protected function getResolver(string $class, string $item): ClassResolverInterface
    {
        return $this->getRepository($class)->getClassResolver($item)
            ?: throw new UnresolvableItemException($item);
    }

    protected function getRepository(string $class): ClassResolverRepositoryInterface
    {
        return $this->repositories[$class]
            ?? throw new UnresolvableSubjectException($class);
    }
}
