<?php

namespace Tests\Support\Concerns;

trait FakeGeneratorTrait
{
    use FakerTrait;
    use GeneratorTrait;

    protected function fakeRandomizer(string ...$types): callable
    {
        return fn () => $this->fake->{$types[array_rand($types)]}();
    }

    protected function uniqueRandomizer(string ...$types): callable
    {
        return fn () => $this->unique->{$types[array_rand($types)]}();
    }

    protected function fakeList(int $count = 10, string ...$types): array
    {
        return $this->dummyList($this->fakeRandomizer(...$types), $count);
    }

    protected function uniqueList(int $count = 10, string ...$types): array
    {
        return $this->dummyList($this->uniqueRandomizer(...$types), $count);
    }

    protected function fakeMap(array $keys, array $types): array
    {
        return $this->dummyMap($this->fakeRandomizer(...$types), $keys);
    }

    protected function uniqueMap(array $keys, array $types): array
    {
        return $this->dummyMap($this->uniqueRandomizer(...$types), $keys);
    }

    protected function fakeAutoKeyedMap(int $count = 10, string ...$types): array
    {
        return $this->dummyAutoKeyedMap(
            fn () => $this->unique->slug(),
            $this->fakeRandomizer(...$types),
            $count
        );
    }

    protected function uniqueAutoKeyedMap(int $count = 10, string ...$types): array
    {
        return $this->dummyAutoKeyedMap(
            fn () => $this->unique->slug(),
            $this->uniqueRandomizer(...$types),
            $count
        );
    }

    protected function fakeValueFactory(string ...$types): callable
    {
        return $this->valueFactory($this->fakeRandomizer(...$types));
    }

    protected function uniqueValueFactory(string ...$types): callable
    {
        return $this->valueFactory($this->uniqueRandomizer(...$types));
    }

    protected function fakeValueGenerator(string ...$types): callable
    {
        return $this->valueGenerator($this->fakeRandomizer(...$types));
    }

    protected function uniqueValueGenerator(string ...$types): callable
    {
        return $this->valueGenerator($this->uniqueRandomizer(...$types));
    }
}
