<?php

namespace WebTheory\Factory\Transformation;

use ReflectionParameter;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;

class OriginalValue implements ArgValueTransformerInterface
{
    public function transformArg(string $key, mixed $val, ReflectionParameter $param): mixed
    {
        return $val;
    }
}
