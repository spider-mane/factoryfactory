<?php

namespace WebTheory\Factory\Resolver;

use ReflectionParameter;
use WebTheory\Factory\Interfaces\DependencyResolverInterface;
use WebTheory\Factory\Interfaces\ObjectResolverInterface;

class ClassEntryObjectResolver implements ObjectResolverInterface
{
    public function __construct(protected DependencyResolverInterface $resolver)
    {
        //
    }

    public function resolveObject(string $item, string $query, array $args, ReflectionParameter $param): object
    {
        return $this->resolver->resolve($item, $query, $args);
    }
}
