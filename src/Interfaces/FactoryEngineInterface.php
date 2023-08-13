<?php

namespace WebTheory\Factory\Interfaces;

interface FactoryEngineInterface
{
    public function generate(string $class, array $args = []): object;
}
