<?php

namespace WebTheory\Factory\Interfaces;

interface DependencyResolverInterface
{
    /**
     * @throws ArgTransformationExceptionInterface
     */
    public function resolve(string $item, string $query, array $args): object;
}
