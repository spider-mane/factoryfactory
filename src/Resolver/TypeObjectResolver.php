<?php

namespace WebTheory\Factory\Resolver;

use ReflectionNamedType;
use ReflectionParameter;
use WebTheory\Factory\Exception\UnresolvableParameterTypeException;
use WebTheory\Factory\Interfaces\ObjectResolverInterface;
use WebTheory\Factory\Interfaces\TypeCasterInterface;

class TypeObjectResolver implements ObjectResolverInterface
{
    public function __construct(protected TypeCasterInterface $caster)
    {
        //
    }

    public function resolveObject(string $item, string $query, array $args, ReflectionParameter $param): object
    {
        $type = $this->getType($param);

        return $this->caster->cast($type, $query, $args);
    }

    protected function getType(ReflectionParameter $param): string
    {
        $type = $param->getType();

        return $type instanceof ReflectionNamedType
            ? $type->getName()
            : throw new UnresolvableParameterTypeException($param);
    }
}
