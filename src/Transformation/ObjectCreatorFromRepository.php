<?php

namespace WebTheory\Factory\Transformation;

use ReflectionParameter;
use WebTheory\Factory\Abstracts\ResolutionEndpointTrait;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;
use WebTheory\Factory\Interfaces\CreationArgumentPolicyInterface;
use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;
use WebTheory\Factory\Transformation\Abstracts\AbstractObjectiveArgumentTransformer;

class ObjectCreatorFromRepository extends AbstractObjectiveArgumentTransformer implements ArgValueTransformerInterface
{
    use ResolutionEndpointTrait;

    public function __construct(
        protected FlexFactoryRepositoryInterface $repository,
        protected CreationArgumentPolicyInterface $policy
    ) {
        //
    }

    protected function resolveObject(string $item, string $query, array $args, ReflectionParameter $param): object
    {
        return $this->getFactory($item)->create($query, $args);
    }

    protected function getFactory(string $item): FlexFactoryInterface
    {
        return $this->repository->getTypeFactory($item)
            ?: throw $this->unresolvableItemException($item);
    }
}
