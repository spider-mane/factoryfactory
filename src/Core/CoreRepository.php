<?php

namespace WebTheory\Factory\Core;

use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\FixedFactoryInterface;
use WebTheory\Factory\Interfaces\FixedFactoryRepositoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryCoreInterface;

class CoreRepository implements FlexFactoryCoreInterface
{
    public function __construct(
        protected FixedFactoryRepositoryInterface $repository,
        protected ClassResolverInterface $resolver
    ) {
        //
    }

    public function process(string $query, array $args = []): object|false
    {
        $resolved = $this->resolveFactory($query);

        return $resolved ? $resolved->create($args) : $resolved;
    }

    protected function resolveFactory(string $class): FixedFactoryInterface|false
    {
        return $this->queryRepository($class)
            ?: $this->resolveAndQueryRepository($class);
    }

    protected function queryRepository(string $class): FixedFactoryInterface|false
    {
        return $this->repository->getClassFactory($class);
    }

    protected function resolveAndQueryRepository(string $class): FixedFactoryInterface|false
    {
        $query = $this->resolver->getClass($class);

        return $query ? $this->queryRepository($class) : $query;
    }
}
