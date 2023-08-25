<?php

namespace WebTheory\Factory\Interfaces;

interface UniversalDependencyResolverInterface
{
    /**
     * @param class-string $for The class where the entry is defined
     * @param string $item The entrypoint on the provided class, mapped to a constructor parameter or setter method
     * @param string $query Class argument to be resolved
     * @param array $args Arguments for building the queried class
     *
     * @return object
     *
     * @throws ArgTransformationExceptionInterface
     */
    public function resolve(string $for, string $item, string $query, array $args): object;
}
