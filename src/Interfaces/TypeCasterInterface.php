<?php

namespace WebTheory\Factory\Interfaces;

interface TypeCasterInterface
{
    /**
     * @param class-string $type
     * @param string $query
     * @param array<string, mixed> $args
     *
     * @return object
     *
     * @throws ArgTransformationExceptionInterface
     */
    public function cast(string $type, string $query, array $args): object;
}
