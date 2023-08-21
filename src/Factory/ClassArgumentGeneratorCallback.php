<?php

namespace WebTheory\Factory\Factory;

use WebTheory\Factory\Interfaces\ClassArgumentGeneratorInterface;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;

class ClassArgumentGeneratorCallback implements ClassArgumentGeneratorInterface
{
    /**
     * @var callable(string, array): ClassArgumentInterface
     */
    protected $callback;

    /**
     * @param callable(string $class, array $args): ClassArgumentInterface $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function create(string $class, array $args = []): ClassArgumentInterface
    {
        return ($this->callback)($class, $args);
    }
}
