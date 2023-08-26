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

    public function resolveObject(string $entry, string $query, array $args, ReflectionParameter $param): object
    {
        $subject = $this->getSubject($param);

        return $this->resolver->resolve($subject, $entry, $query, $args);
    }

    protected function getSubject(ReflectionParameter $param): string
    {
        return $param->getDeclaringClass()->getName();
    }
}
