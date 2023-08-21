<?php

namespace WebTheory\Factory\Transformation;

use ReflectionParameter;
use WebTheory\Factory\Abstracts\ResolutionEndpointTrait;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\ClassResolverRepositoryInterface;
use WebTheory\Factory\Interfaces\CreationArgumentPolicyInterface;
use WebTheory\Factory\Transformation\Abstracts\AbstractObjectiveArgumentTransformer;

class ClassArgumentResolver extends AbstractObjectiveArgumentTransformer implements ArgValueTransformerInterface
{
    use ResolutionEndpointTrait;

    public function __construct(
        protected ClassResolverRepositoryInterface $repository,
        protected CreationArgumentPolicyInterface $policy
    ) {
        //
    }

    protected function resolveObject(string $item, string $query, array $args, ReflectionParameter $param): ClassArgumentInterface
    {
        return new ClassArgument($this->getClass($item, $query), $args);
    }

    protected function getClass(string $item, string $query): string
    {
        return $this->getResolver($item)->getClass($query)
            ?: throw $this->unresolvableQueryException($query);
    }

    protected function getResolver(string $item): ClassResolverInterface
    {
        return $this->repository->getClassResolver($item)
            ?: throw $this->unresolvableItemException($item);
    }
}
