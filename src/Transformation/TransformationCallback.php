<?php

namespace WebTheory\Factory\Transformation;

use ReflectionParameter;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;

class TransformationCallback implements ArgValueTransformerInterface
{
    /**
     * @var callable(string, mixed, ReflectionParameter): mixed
     */
    protected $callback;

    /**
     * @param callable(string $key, mixed $val, ReflectionParameter $param): mixed $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function transformArg(string $key, mixed $val, ReflectionParameter $param): mixed
    {
        return ($this->callback)($key, $val, $param);
    }
}
