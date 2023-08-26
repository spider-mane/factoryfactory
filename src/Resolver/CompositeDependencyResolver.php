<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\DependencyResolverInterface;
use WebTheory\Factory\Resolver\Abstracts\CompositeObjectResolverTrait;

class CompositeDependencyResolver implements DependencyResolverInterface
{
    use CompositeObjectResolverTrait;

    /**
     * @var iterable<DependencyResolverInterface>
     */
    protected iterable $resolvers;

    public function __construct(DependencyResolverInterface ...$resolvers)
    {
        $this->resolvers = $resolvers;
    }

    public function resolve(string $item, string $query, array $args): object
    {
        return $this->resolveFromComposition(
            $this->resolvers,
            __FUNCTION__,
            ...func_get_args()
        );
    }
}
