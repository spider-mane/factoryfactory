<?php

namespace WebTheory\Factory\Interfaces;

interface DependencyResolverInterface
{
    /**
     * @throws ArgTransformationExceptionInterface
     */
    public function resolve(string $entry, string $query, array $args): object;
}
