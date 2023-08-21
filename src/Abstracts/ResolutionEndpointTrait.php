<?php

namespace WebTheory\Factory\Abstracts;

use WebTheory\Factory\Exception\UnresolvableItemException;
use WebTheory\Factory\Exception\UnresolvableQueryException;
use WebTheory\Factory\Exception\UnresolvableSubjectException;

trait ResolutionEndpointTrait
{
    /**
     * @param class-string $class
     */
    protected function unresolvableSubjectException(string $class): UnresolvableSubjectException
    {
        return new UnresolvableSubjectException($class);
    }

    protected function unresolvableItemException(string $item): UnresolvableItemException
    {
        return new UnresolvableItemException($item);
    }

    protected function unresolvableQueryException(string $query): UnresolvableQueryException
    {
        return new UnresolvableQueryException($query);
    }
}
