<?php

namespace WebTheory\Factory\Transformation;

use InvalidArgumentException;
use ReflectionParameter;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\ClassResolverRepositoryInterface;

class ClassArgumentResolver implements ArgValueTransformerInterface
{
    public const DEFAULT_CLASS_KEY = '@create';

    public function __construct(
        protected ClassResolverRepositoryInterface $repository,
        protected string $classKey = self::DEFAULT_CLASS_KEY
    ) {
        //
    }

    /**
     * @return mixed|ClassArgumentInterface
     */
    public function transformArg(string $key, mixed $val, ReflectionParameter $param): mixed
    {
        return $this->isCreationData($val)
            ? $this->getClassArgument($key, $val)
            : $val;
    }

    protected function isCreationData(mixed $arg): bool
    {
        return is_array($arg) && isset($arg[$this->classKey]);
    }

    protected function getClassArgument(string $key, mixed $args): ClassArgumentInterface
    {
        $create = $args[$this->classKey];

        unset($args[$this->classKey]);

        return new ClassArgument(
            $this->getClass($this->getResolver($key), $create),
            $args
        );
    }

    protected function getResolver(string $key): ClassResolverInterface
    {
        return $this->repository->getClassResolver($key)
            ?: throw new InvalidArgumentException();
    }

    protected function getClass(ClassResolverInterface $resolver, string $arg): string
    {
        return $resolver->getClass($arg)
            ?: throw new InvalidArgumentException();
    }
}
