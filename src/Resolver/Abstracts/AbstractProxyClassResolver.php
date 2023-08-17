<?php

namespace WebTheory\Factory\Resolver\Abstracts;

use WebTheory\Factory\Interfaces\ClassResolverInterface;

abstract class AbstractProxyClassResolver extends AbstractClassResolver implements ClassResolverInterface
{
    protected ClassResolverInterface $handler;

    protected function performQuery(string $arg): string|false
    {
        return $this->handler->getClass($arg);
    }
}
