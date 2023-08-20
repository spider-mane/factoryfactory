<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\CreationArgumentPolicyInterface;

class CreationKey implements CreationArgumentPolicyInterface
{
    public function __construct(protected string $key = "@create")
    {
        //
    }

    public function isCreationData(mixed $arg): bool
    {
        return is_array($arg) && isset($arg[$this->key]);
    }

    public function extractCreationQuery(mixed $arg): string
    {
        return $arg[$this->key];
    }

    public function normalizeArgs(mixed $entry): array
    {
        unset($entry[$this->key]);

        return $entry;
    }
}
