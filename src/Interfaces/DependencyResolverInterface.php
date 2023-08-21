<?php

namespace WebTheory\Factory\Interfaces;

interface DependencyResolverInterface
{
    public function resolve(string $item, string $query, array $args): object;
}
