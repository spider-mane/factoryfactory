<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Exception\UnresolvableTypeException;
use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;
use WebTheory\Factory\Interfaces\TypeCasterInterface;

class TypeFlexFactoryRouter implements TypeCasterInterface
{
    public function __construct(protected FlexFactoryRepositoryInterface $repository)
    {
        //
    }

    public function cast(string $type, string $query, array $args): object
    {
        return $this->getHandler($type)->create($query, $args);
    }

    protected function getHandler(string $type): FlexFactoryInterface
    {
        return $this->repository->getTypeFactory($type)
            ?: throw new UnresolvableTypeException($type);
    }
}
