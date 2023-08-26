<?php

namespace WebTheory\Factory\Interfaces;

interface UniversalDependencyResolverInterface
{
    /**
     * @param class-string $subject The class where the entry is defined
     * @param string $entry The entrypoint on the subject class, mapped to a constructor parameter or setter method
     * @param string $query Class argument to be resolved
     * @param array $args Arguments for building the queried class
     *
     * @return object
     *
     * @throws ArgTransformationExceptionInterface
     */
    public function resolve(string $subject, string $entry, string $query, array $args): object;
}
