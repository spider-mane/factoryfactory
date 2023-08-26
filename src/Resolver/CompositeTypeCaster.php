<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\TypeCasterInterface;
use WebTheory\Factory\Resolver\Abstracts\CompositeObjectResolverTrait;

class CompositeTypeCaster implements TypeCasterInterface
{
    use CompositeObjectResolverTrait;

    /**
     * @var iterable<TypeCasterInterface>
     */
    protected iterable $casters;

    public function __construct(TypeCasterInterface ...$casters)
    {
        $this->casters = $casters;
    }

    public function cast(string $type, string $query, array $args): object
    {
        return $this->resolveFromComposition(
            $this->casters,
            __FUNCTION__,
            ...func_get_args()
        );
    }
}
