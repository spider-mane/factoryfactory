<?php

namespace WebTheory\Factory\Interfaces;

interface ClassResolverRepositoryInterface
{
    public function getClassResolver(string $type): ClassResolverInterface|false;
}
