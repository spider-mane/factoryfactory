<?php

declare(strict_types=1);

namespace Tests\Support\Concerns;

use Tests\Support\LIb\FormattedDataSet;

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

    /**
     * Data set
     */
    protected static function ds(): FormattedDataSet
    {
        return new FormattedDataSet();
    }

    protected static function data(string $key, string $val): FormattedDataSet
    {
        return static::ds()->set($key, $val);
    }

    /**
     * "Method Under Test" template for use as keys in data providers.
     */
    protected static function mut(string $method): FormattedDataSet
    {
        return static::ds()->method($method);
    }

    /**
     * Interface under test
     */
    protected static function iut(string $interface): FormattedDataSet
    {
        return static::ds()->set('interface', $interface);
    }
}
