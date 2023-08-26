<?php

namespace WebTheory\Factory\Resolver;

use ReflectionParameter;
use WebTheory\Factory\Interfaces\ObjectResolverInterface;
use WebTheory\Factory\Interfaces\UniversalDependencyResolverInterface;

class SystemObjectResolver implements ObjectResolverInterface
{
    public function __construct(protected UniversalDependencyResolverInterface $resolver)
    {
        //
    }

    public function resolveObject(string $item, string $query, array $args, ReflectionParameter $param): object
    {
        $for = $this->getSubject($param);

        return $this->resolver->resolve($for, $item, $query, $args);
    }

    protected function getSubject(ReflectionParameter $param): string
    {
        return $param->getDeclaringClass()->getName();
    }
}
