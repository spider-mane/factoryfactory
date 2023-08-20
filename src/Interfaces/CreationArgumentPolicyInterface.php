<?php

namespace WebTheory\Factory\Interfaces;

interface CreationArgumentPolicyInterface
{
    public function isCreationData(mixed $arg): bool;

    public function extractCreationQuery(mixed $arg): string;

    /**
     * @return array<string, mixed>
     */
    public function normalizeArgs(mixed $entry): array;
}
