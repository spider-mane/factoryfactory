<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\DependencyResolverInterface;
use WebTheory\Factory\Interfaces\UniversalDependencyResolverInterface;
use WebTheory\Factory\Resolver\Abstracts\DependencyResolverTrait;

class DependencyResolverRouter implements UniversalDependencyResolverInterface
{
    use DependencyResolverTrait;

    /**
     * @param array<class-string, DependencyResolverInterface> $resolvers
     */
    public function __construct(protected array $resolvers)
    {
        //
    }

    public function resolve(string $for, string $item, string $query, array $args): object
    {
        return $this->getResolver($for)->resolve($item, $query, $args);
    }

    protected function getResolver(string $class): DependencyResolverInterface
    {
        return $this->resolvers[$class]
            ?? throw $this->unresolvableClassException($class);
    }
}
