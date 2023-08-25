<?php

namespace WebTheory\Factory\Resolver\Abstracts;

use WebTheory\Factory\Interfaces\ArgTransformationExceptionInterface;

trait CompositeObjectResolverTrait
{
    protected function resolveFromComposition(iterable $resolvers, string $method, mixed ...$args): object
    {
        $exception = null;

        foreach ($resolvers as $resolver) {
            try {
                return $resolver->{$method}(...$args);
            } catch (ArgTransformationExceptionInterface $exception) {
                //
            }
        }

        throw $exception;
    }
}
