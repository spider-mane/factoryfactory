<?php

namespace WebTheory\Factory\Transformation;

use ReflectionParameter;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;
use WebTheory\Factory\Interfaces\CreationArgumentPolicyInterface;
use WebTheory\Factory\Interfaces\ObjectResolverInterface;

class ObjectiveArgumentTransformer implements ArgValueTransformerInterface
{
    public function __construct(
        protected CreationArgumentPolicyInterface $policy,
        protected ObjectResolverInterface $resolver
    ) {
        //
    }

    public function transformArg(string $key, mixed $val, ReflectionParameter $param): mixed
    {
        if (!$this->policy->isCreationData($val)) {
            return $val;
        }

        $query = $this->policy->extractCreationQuery($val);
        $args = $this->policy->normalizeArgs($val);

        return $this->resolver->resolveObject($key, $query, $args, $param);
    }
}
