<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\UniversalDependencyResolverInterface;
use WebTheory\Factory\Resolver\Abstracts\CompositeObjectResolverTrait;

class CompositeUniversalDependencyResolver implements UniversalDependencyResolverInterface
{
    use CompositeObjectResolverTrait;

    /**
     * @var iterable<UniversalDependencyResolverInterface>
     */
    protected iterable $resolvers;

    public function __construct(UniversalDependencyResolverInterface ...$resolvers)
    {
        $this->resolvers = $resolvers;
    }

    public function resolve(string $subject, string $entry, string $query, array $args): object
    {
        return $this->resolveFromComposition(
            $this->resolvers,
            __FUNCTION__,
            ...func_get_args()
        );
    }
}
