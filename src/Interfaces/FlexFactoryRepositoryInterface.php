<?php

namespace WebTheory\Factory\Interfaces;

interface FlexFactoryRepositoryInterface
{
    public function getTypeFactory(string $type): FlexFactoryInterface|false;
}
