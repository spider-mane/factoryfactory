<?php

namespace WebTheory\Factory\Interfaces;

use ReflectionParameter;

interface ArgValueTransformerInterface
{
    public function transformArg(string $key, mixed $val, ReflectionParameter $param): mixed;
}
