<?php

namespace WebTheory\Factory\Abstracts;

use InvalidArgumentException;

trait ResolutionEndpointTrait
{
    protected function unresolvableSubjectException(string $class): InvalidArgumentException
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
