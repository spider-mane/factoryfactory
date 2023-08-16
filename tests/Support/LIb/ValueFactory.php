<?php

namespace Tests\Support\LIb;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class ValueFactory implements ArrayAccess, IteratorAggregate
{
    protected array $repository = [];

    public function __construct(protected callable $generator)
    {
        //
    }

    public function __invoke(int|string $item = PHP_INT_MAX): mixed
    {
        return '*' === $item
            ? $this->repository
            : $this->repository[$item] ??= ($this->generator)($item);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this($offset);
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->repository);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->repository[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->repository[$offset]);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->repository);
    }
}
