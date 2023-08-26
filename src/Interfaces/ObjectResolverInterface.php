<?php

namespace WebTheory\Factory\Interfaces;

use ReflectionParameter;

interface ObjectResolverInterface
{
    /**
     * @throws ArgTransformationExceptionInterface
     */
    public function resolveObject(string $item, string $query, array $args, ReflectionParameter $param): object;
}
