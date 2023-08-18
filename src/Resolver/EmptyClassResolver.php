<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\ClassResolverInterface;

class EmptyClassResolver implements ClassResolverInterface
{
    public function getClass(string $arg): string|false
    {
        return false;
    }
}
