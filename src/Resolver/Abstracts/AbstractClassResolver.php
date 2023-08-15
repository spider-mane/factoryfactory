<?php

namespace WebTheory\Factory\Resolver\Abstracts;

use WebTheory\Factory\Interfaces\ClassResolverInterface;

abstract class AbstractClassResolver implements ClassResolverInterface
{
    /**
     * @var array<string,class-string>
     */
    protected array $cache = [];

    public function getClass(string $arg): string|false
    {
        return $this->cache[$arg] ??= $this->performQuery($arg);
    }

    /**
     * @return class-string|false
     */
    abstract protected function performQuery(string $arg): string|false;
}
