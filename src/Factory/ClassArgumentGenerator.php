<?php

namespace WebTheory\Factory\Factory;

use WebTheory\Factory\Interfaces\ClassArgumentGeneratorInterface;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Resolver\Abstracts\DependencyResolverTrait;
use WebTheory\Factory\Transformation\ClassArgument;

class ClassArgumentGenerator implements ClassArgumentGeneratorInterface
{
    use DependencyResolverTrait;

    public function __construct(protected ClassResolverInterface $classResolver)
    {
        //
    }

    public function create(string $query, array $args = []): ClassArgumentInterface
    {
        return new ClassArgument($this->getClass($query), $args);
    }

    protected function getClass(string $query): string
    {
        return $this->classResolver->getClass($query)
            ?: throw $this->unresolvableQueryException($query);
    }
}
