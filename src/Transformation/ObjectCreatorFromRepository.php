<?php

namespace WebTheory\Factory\Transformation;

use InvalidArgumentException;
use ReflectionParameter;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;

class ObjectCreatorFromRepository implements ArgValueTransformerInterface
{
    public const DEFAULT_CLASS_KEY = '@create';

    public function __construct(
        protected FlexFactoryRepositoryInterface $repository,
        protected string $classKey = self::DEFAULT_CLASS_KEY
    ) {
        $this->repository = $repository;
        $this->classKey = $classKey;
    }

    public function transformArg(string $key, mixed $val, ReflectionParameter $param): mixed
    {
        return $this->isCreationData($val)
            ? $this->createObjectFromArgs($key, $val)
            : $val;
    }

    protected function isCreationData(mixed $arg): bool
    {
        return is_array($arg) && array_key_exists($this->classKey, $arg);
    }

    protected function createObjectFromArgs(string $key, array $args): object
    {
        $create = $args[$this->classKey];

        unset($args[$this->classKey]);

        if ($factory = $this->repository->getTypeFactory($key)) {
            return $factory->create($create, $args);
        } else {
            throw $this->unresolvableCreationArgException($key, $create);
        }
    }

    protected function unresolvableCreationArgException(string $key, string $arg): InvalidArgumentException
    {
        return new InvalidArgumentException(
            "Unable to create instance of {$arg} for argument {$key}."
        );
    }
}
