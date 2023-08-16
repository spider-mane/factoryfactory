<?php

declare(strict_types=1);

namespace Tests\Support\Concerns;

use ArrayAccess;
use Generator;
use Tests\Support\LIb\ValueFactory;
use Traversable;

trait GeneratorTrait
{
    /**
     * Returns a callable that when invoked randomly selects a provided
     * generator to invoke in turn.
     */
    protected static function generatorRandomizer(callable ...$generators): callable
    {
        return fn () => ($generators[array_rand($generators)])();
    }

    /**
     * Create an indexed array of specified count using a callback to generate
     * each entry.
     *
     * @return list<mixed>
     */
    protected static function dummyList(callable $generator, int $count = 10): array
    {
        return array_map(fn () => $generator(), range(1, $count));
    }

    /**
     * Create an associative array using a callback to generate entries with
     * keys derived from a provided list.
     */
    protected static function dummyMap(callable $generator, array $keys): array
    {
        return array_map(fn () => $generator(), array_flip($keys));
    }

    /**
     * Create an associative array of specified count using callbacks to
     * generate both keys and entries.
     */
    protected static function dummyAutoKeyedMap(callable $keyGen, callable $valueGen, int $count = 10): array
    {
        return static::dummyMap($valueGen, static::dummyList($keyGen, $count));
    }

    /**
     * Returns a traversable, callable, array-accessible object that creates
     * values on demand using the provided generator.  Created values can be
     * later referenced with the same input value. Use '*' to get an array
     * containing all values.
     *
     * @return ArrayAccess&Traversable&callable(int|string $item): mixed
     */
    protected static function valueFactory(callable $generator): callable
    {
        return new ValueFactory($generator);
    }

    /**
     * Returns a generator function that yields the return value provided by the
     * $generator argument for a definable amount of times.
     *
     * @see https://www.php.net/manual/en/language.generators.overview.php
     *
     * @return callable(int $count): Generator
     */
    protected static function valueGenerator(callable $generator): callable
    {
        return static function (int $count) use ($generator): Generator {
            for ($i = 0; $i < $count; $i++) {
                yield $generator();
            }
        };
    }
}
