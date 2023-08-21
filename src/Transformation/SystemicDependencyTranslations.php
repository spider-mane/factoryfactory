<?php

namespace WebTheory\Factory\Transformation;

use ReflectionParameter;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;
use WebTheory\Factory\Interfaces\CreationArgumentPolicyInterface;
use WebTheory\Factory\Interfaces\UniversalDependencyResolverInterface;
use WebTheory\Factory\Transformation\Abstracts\AbstractObjectiveArgumentTransformer;

class SystemicDependencyTranslations extends AbstractObjectiveArgumentTransformer implements ArgValueTransformerInterface
{
    public function __construct(
        protected UniversalDependencyResolverInterface $resolver,
        protected CreationArgumentPolicyInterface $policy
    ) {
        //
    }

    protected function resolveObject(string $key, string $query, array $args, ReflectionParameter $param): object
    {
        $for = $param->getDeclaringClass()->getName();

        return $this->resolver->resolve($for, $key, $query, $args);
    }
}
