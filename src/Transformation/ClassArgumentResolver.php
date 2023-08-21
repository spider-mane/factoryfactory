<?php

namespace WebTheory\Factory\Transformation;

use InvalidArgumentException;
use ReflectionParameter;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\ClassResolverRepositoryInterface;
use WebTheory\Factory\Interfaces\CreationArgumentPolicyInterface;
use WebTheory\Factory\Transformation\Abstracts\AbstractObjectiveArgumentTransformer;

class ClassArgumentResolver extends AbstractObjectiveArgumentTransformer implements ArgValueTransformerInterface
{
    public function __construct(
        protected ClassResolverRepositoryInterface $repository,
        protected CreationArgumentPolicyInterface $policy
    ) {
        //
    }

    protected function resolveObject(string $key, string $query, array $args, ReflectionParameter $param): ClassArgumentInterface
    {
        $class = $this->getClass($this->getResolver($key), $query);

        return new ClassArgument($class, $args);
    }

    protected function getResolver(string $item): ClassResolverInterface
    {
        return $this->repository->getClassResolver($item)
            ?: throw $this->unresolvableResolverException($item);
    }

    protected function unresolvableResolverException(string $item): InvalidArgumentException
    {
        return new InvalidArgumentException(
            "Unable to redefine value for {$item}."
        );
    }

    protected function getClass(ClassResolverInterface $resolver, string $query): string
    {
        return $resolver->getClass($query)
            ?: throw $this->unresolvableClassException($query);
    }

    protected function unresolvableClassException(string $query): InvalidArgumentException
    {
        return new InvalidArgumentException(
            "Argument {$query} could not be resolved as a class."
        );
    }
}
