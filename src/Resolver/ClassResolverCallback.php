<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\ClassResolverInterface;

class ClassResolverCallback implements ClassResolverInterface
{
    /**
     * @var callable(string): (string|false)
     */
    protected $callback;

    /**
     * @param callable(string $arg): (string|false) $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function getClass(string $arg): string|false
    {
        return ($this->callback)($arg);
    }
}
