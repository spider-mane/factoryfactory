<?php

namespace WebTheory\Factory\Interfaces;

interface FactoryEngineInterface
{
    /**
     * @param class-string $class
     * @param array<string, mixed> $args
     *
     * @return object
     */
    public function generate(string $class, array $args = []): object;
}
