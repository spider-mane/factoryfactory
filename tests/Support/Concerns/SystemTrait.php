<?php

namespace Tests\Support\Concerns;

trait SystemTrait
{
    /**
     * Call a method on an object with provided arguments. Helpful for tests
     * whose actions are defined by data providers.
     */
    protected static function performSystemAction(object $system, string $method, array $args): mixed
    {
        return $system->{$method}(...array_values($args));
    }
}
