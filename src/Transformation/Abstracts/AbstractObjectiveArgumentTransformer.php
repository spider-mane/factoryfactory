<?php

namespace WebTheory\Factory\Transformation\Abstracts;

use ReflectionParameter;
use WebTheory\Factory\Interfaces\CreationArgumentPolicyInterface;

abstract class AbstractObjectiveArgumentTransformer
{
    protected CreationArgumentPolicyInterface $policy;

    public function transformArg(string $key, mixed $val, ReflectionParameter $param): mixed
    {
        if (!$this->policy->isCreationData($val)) {
            return $val;
        }

        $query = $this->policy->extractCreationQuery($val);
        $args = $this->policy->normalizeArgs($val);

        return $this->resolveObject($key, $query, $args, $param);
    }

    abstract protected function resolveObject(string $item, string $query, array $args, ReflectionParameter $param): object;
}
