<?php

namespace WebTheory\Factory;

use WebTheory\Factory\Interfaces\FactoryRepositoryInterface;
use WebTheory\Factory\Interfaces\FactoryInterface;
use WebTheory\Factory\Traits\ClassResolverTrait;
use WebTheory\Factory\Traits\SmartFactoryTrait;

abstract class Factory implements FactoryInterface
{
    use SmartFactoryTrait;
    use ClassResolverTrait;

    protected $repository;

    public function __construct(FactoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    protected function getFactory(string $interface)
    {
        return $this->repository->getFactoryFor($this->getClassName($interface));
    }

    protected function resolveArg()
    {
        //
    }

    abstract protected function getClassFactories(): array;

    abstract protected function getNamespaces(): array;
}
