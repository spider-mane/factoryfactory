<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;
use WebTheory\Factory\Interfaces\UniversalDependencyResolverInterface;
use WebTheory\Factory\Resolver\Abstracts\DependencyResolverTrait;

class FlexFactoryTree implements UniversalDependencyResolverInterface
{
    use DependencyResolverTrait;

    /**
     * @param array<class-string, FlexFactoryRepositoryInterface> $tree
     */
    public function __construct(protected array $tree)
    {
        //
    }

    public function resolve(string $for, string $item, string $query, array $args): object
    {
        return $this->getNode($for, $item)->create($query, $args);
    }

    protected function getNode(string $class, string $item): FlexFactoryInterface
    {
        return $this->getBranch($class)->getTypeFactory($item)
            ?: throw $this->unresolvableItemException($item);
    }

    protected function getBranch(string $class): FlexFactoryRepositoryInterface
    {
        return $this->tree[$class]
            ?? throw $this->unresolvableClassException($class);
    }
}
