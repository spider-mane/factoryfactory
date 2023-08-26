<?php

namespace WebTheory\Factory\Resolver;

use ArrayAccess;
use WebTheory\Factory\Exception\UnresolvableSubjectException;
use WebTheory\Factory\Interfaces\DependencyResolverInterface;
use WebTheory\Factory\Interfaces\UniversalDependencyResolverInterface;

class DependencyResolverRouter implements UniversalDependencyResolverInterface
{
    /**
     * @param array<class-string, DependencyResolverInterface> $resolvers
     */
    public function __construct(protected array|ArrayAccess $resolvers)
    {
        //
    }

    public function resolve(string $subject, string $entry, string $query, array $args): object
    {
        return $this->getResolver($subject)->resolve($entry, $query, $args);
    }

    protected function getResolver(string $class): DependencyResolverInterface
    {
        return $this->resolvers[$class]
            ?? throw new UnresolvableSubjectException($class);
    }
}
