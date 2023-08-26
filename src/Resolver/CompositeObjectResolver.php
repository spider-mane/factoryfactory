<?php

namespace WebTheory\Factory\Resolver;

use ReflectionParameter;
use WebTheory\Factory\Interfaces\ObjectResolverInterface;
use WebTheory\Factory\Resolver\Abstracts\CompositeObjectResolverTrait;

class CompositeObjectResolver implements ObjectResolverInterface
{
    use CompositeObjectResolverTrait;

    /**
     * @var iterable<ObjectResolverInterface>
     */
    protected iterable $resolvers;

    public function __construct(ObjectResolverInterface ...$resolvers)
    {
        $this->resolvers = $resolvers;
    }

    public function resolveObject(string $item, string $query, array $args, ReflectionParameter $param): object
    {
        return $this->resolveFromComposition(
            $this->resolvers,
            __FUNCTION__,
            ...func_get_args()
        );
    }
}
