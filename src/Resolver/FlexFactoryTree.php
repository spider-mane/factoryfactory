<?php

namespace WebTheory\Factory\Resolver;

use ArrayAccess;
use WebTheory\Factory\Exception\UnresolvableItemException;
use WebTheory\Factory\Exception\UnresolvableSubjectException;
use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;
use WebTheory\Factory\Interfaces\UniversalDependencyResolverInterface;

class FlexFactoryTree implements UniversalDependencyResolverInterface
{
    /**
     * @param array<class-string, FlexFactoryRepositoryInterface> $tree
     */
    public function __construct(protected array|ArrayAccess $tree)
    {
        //
    }

    public function resolve(string $for, string $item, string $query, array $args): object
    {
        return $this->getFactory($for, $item)->create($query, $args);
    }

    protected function getFactory(string $class, string $item): FlexFactoryInterface
    {
        return $this->getRepository($class)->getTypeFactory($item)
            ?: throw new UnresolvableItemException($item);
    }

    protected function getRepository(string $class): FlexFactoryRepositoryInterface
    {
        return $this->tree[$class]
            ?? throw new UnresolvableSubjectException($class);
    }
}
