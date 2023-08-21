<?php

namespace WebTheory\Factory\Resolver\Abstracts;

use InvalidArgumentException;

trait DependencyResolverTrait
{
    protected function unresolvableClassException(string $class): InvalidArgumentException
    {
        return new InvalidArgumentException(
            "Unable to resolve arguments on class \"{$class}.\""
        );
    }

    protected function unresolvableItemException(string $item): InvalidArgumentException
    {
        return new InvalidArgumentException(
            "Unable to resolve for item \"{$item}.\"."
        );
    }

    protected function unresolvableQueryException(string $query): InvalidArgumentException
    {
        return new InvalidArgumentException(
            "Unable to resolve argument \"{$query}.\""
        );
    }
}
