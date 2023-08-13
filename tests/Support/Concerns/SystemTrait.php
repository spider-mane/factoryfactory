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
    protected static function mut(string $method, string $extra = ''): string
    {
        $method = static::ds()->method($method)->get();

        return $extra ? "{$method}, {$extra}" : $method;
    }

    /**
     * Interface under test
     */
    protected static function iut(string $interface, string $extra = ''): string
    {
        $interface = static::ds()->set('interface', $interface)->get();

        return $extra ? "{$interface}, {$extra}" : $interface;
    }
}
