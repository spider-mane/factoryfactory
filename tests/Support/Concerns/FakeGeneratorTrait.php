<?php

namespace Tests\Support\Concerns;

trait FakeGeneratorTrait
{
    use FakerTrait;
    use GeneratorTrait;

    protected function fakeMap(array $keys, array $types): array
    {
        return $this->dummyMap($this->fakeGeneratorFactory(...$types), $keys);
    }

    protected function uniqueMap(array $keys, array $types): array
    {
        return $this->dummyMap($this->uniqueGeneratorFactory(...$types), $keys);
    }

    protected function fakeGeneratorFactory(string ...$types): callable
    {
        $set = $this->fakeGeneratorSet(...$types);

        return fn () => ($set[array_rand($set)])();
    }

    protected function uniqueGeneratorFactory(string ...$types): callable
    {
        $set = $this->uniqueGeneratorSet(...$types);

        return fn () => ($set[array_rand($set)])();
    }

    protected function fakeGeneratorSet(string ...$types): array
    {
        return array_map(
            fn ($type) => fn () => $this->fake->{$type}(),
            $types
        );
    }

    protected function uniqueGeneratorSet(string ...$types): array
    {
        return array_map(
            fn ($type) => fn () => $this->unique->{$type}(),
            $types
        );
    }

    protected function fakeValueFactory(string ...$types): callable
    {
        return $this->valueFactory(...$this->fakeGeneratorSet(...$types));
    }

    protected function uniqueValueFactory(string ...$types): callable
    {
        return $this->valueFactory(...$this->uniqueGeneratorSet(...$types));
    }
}
