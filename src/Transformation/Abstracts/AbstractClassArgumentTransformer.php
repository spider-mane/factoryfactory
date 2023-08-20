<?php

namespace WebTheory\Factory\Transformation\Abstracts;

use ReflectionParameter;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;

abstract class AbstractClassArgumentTransformer extends AbstractObjectiveArgumentTransformer
{
    abstract protected function resolveObject(string $key, string $query, array $args, ReflectionParameter $param): ClassArgumentInterface;
}
