<?php

declare(strict_types=1);

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
        if (!$factory = $this->resolveFactory($query)) {
            return false;
        }

        return $factory->create($args);
    }

    protected function resolveFactory(string $query): FixedFactoryInterface|false
    {
        return $this->queryRepository($query)
            ?: $this->resolveAndQueryRepository($query);
    }

    protected function queryRepository(string $query): FixedFactoryInterface|false
    {
        return $this->repository->getClassFactory($query);
    }

    protected function resolveAndQueryRepository(string $query): FixedFactoryInterface|false
    {
        if (!$class = $this->resolver->getClass($query)) {
            return false;
        }

        return $this->queryRepository($class);
    }
}
