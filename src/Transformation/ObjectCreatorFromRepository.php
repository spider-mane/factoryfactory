<?php

namespace WebTheory\Factory\Transformation;

use InvalidArgumentException;
use ReflectionParameter;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;
use WebTheory\Factory\Interfaces\CreationArgumentPolicyInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;
use WebTheory\Factory\Transformation\Abstracts\AbstractObjectiveArgumentTransformer;

class ObjectCreatorFromRepository extends AbstractObjectiveArgumentTransformer implements ArgValueTransformerInterface
{
    public function __construct(
        protected FlexFactoryRepositoryInterface $repository,
        protected CreationArgumentPolicyInterface $policy
    ) {
        //
    }

    protected function resolveObject(string $key, string $query, array $args, ReflectionParameter $param): object
    {
        if ($factory = $this->repository->getTypeFactory($key)) {
            return $factory->create($query, $args);
        }

        throw $this->unresolvableCreationArgException($key, $query);
    }

    protected function unresolvableCreationArgException(string $key, string $arg): InvalidArgumentException
    {
        return new InvalidArgumentException(
            "Unable to create instance of {$arg} for argument {$key}."
        );
    }
}
