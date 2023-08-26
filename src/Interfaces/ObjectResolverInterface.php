<?php

namespace WebTheory\Factory\Interfaces;

use ReflectionParameter;

interface ObjectResolverInterface
{
    /**
     * @throws ArgTransformationExceptionInterface
     */
    public function resolveObject(string $entry, string $query, array $args, ReflectionParameter $param): object;
}
