<?php

namespace WebTheory\Factory\Transformation;

use InvalidArgumentException;
use ReflectionParameter;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\ClassResolverRepositoryInterface;
use WebTheory\Factory\Interfaces\CreationArgumentPolicyInterface;
use WebTheory\Factory\Transformation\Abstracts\AbstractClassArgumentTransformer;

class ClassArgumentResolver extends AbstractClassArgumentTransformer implements ArgValueTransformerInterface
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

    protected function getResolver(string $key): ClassResolverInterface
    {
        return $this->repository->getClassResolver($key)
            ?: throw $this->unresolvableKeyException($key);
    }

    protected function getClass(ClassResolverInterface $resolver, string $query): string
    {
        return $resolver->getClass($query)
            ?: throw $this->unresolvableClassArgument($query);
    }

    protected function unresolvableKeyException(string $key): InvalidArgumentException
    {
        return new InvalidArgumentException(
            "Unable to redefine value for {$key}."
        );
    }

    protected function unresolvableClassArgument(string $query): InvalidArgumentException
    {
        return new InvalidArgumentException(
            "Argument {$query} could not be resolved as a class."
        );
    }
}
