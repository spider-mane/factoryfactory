<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Exception\UnresolvableQueryException;
use WebTheory\Factory\Exception\UnresolvableTypeException;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\ClassResolverRepositoryInterface;
use WebTheory\Factory\Interfaces\TypeCasterInterface;
use WebTheory\Factory\Transformation\ClassArgument;

class ClassArgumentTypes implements TypeCasterInterface
{
    public function __construct(protected ClassResolverRepositoryInterface $repository)
    {
        //
    }

    public function cast(string $type, string $query, array $args): ClassArgumentInterface
    {
        return new ClassArgument($this->getClass($type, $query), $args);
    }

    protected function getClass(string $type, string $query): string
    {
        return $this->getResolver($type)->getClass($query)
            ?: throw new UnresolvableQueryException($query);
    }

    protected function getResolver(string $type): ClassResolverInterface
    {
        return $this->repository->getClassResolver($type)
            ?: throw new UnresolvableTypeException($type);
    }
}
