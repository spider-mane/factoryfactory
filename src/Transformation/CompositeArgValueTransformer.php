<?php

namespace WebTheory\Factory\Transformation;

use ReflectionParameter;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;

class CompositeArgValueTransformer implements ArgValueTransformerInterface
{
    /**
     * @var iterable<ArgValueTransformerInterface>
     */
    protected iterable $transformers;

    public function __construct(ArgValueTransformerInterface ...$transformers)
    {
        $this->transformers = $transformers;
    }

    public function transformArg(string $key, mixed $val, ReflectionParameter $param): mixed
    {
        foreach ($this->transformers as $transformer) {
            $val = $transformer->transformArg($key, $val, $param);
        }

        return $val;
    }
}
