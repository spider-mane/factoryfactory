<?php

namespace WebTheory\Factory\Resolver\Abstracts;

use WebTheory\Factory\Interfaces\ClassResolverInterface;

abstract class AbstractProxyClassResolver implements ClassResolverInterface
{
    protected ClassResolverInterface $handler;

    public function getClass(string $arg): string|false
    {
        return $this->handler->getClass($arg);
    }
}
