<?php

namespace WebTheory\Factory\Interfaces;

interface ClassArgumentGeneratorInterface extends FlexFactoryInterface
{
    public function create(string $query, array $args = []): ClassArgumentInterface;
}
