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

    protected function resolveObject(string $item, string $query, array $args, ReflectionParameter $param): object
    {
        $for = $this->getSubject($param);

        return $this->resolver->resolve($for, $item, $query, $args);
    }

    protected function getSubject(ReflectionParameter $param): string
    {
        return $param->getDeclaringClass()->getName();
    }
}
