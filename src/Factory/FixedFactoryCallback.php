<?php

namespace WebTheory\Factory\Factory;

use WebTheory\Factory\Interfaces\FixedFactoryInterface;

class FixedFactoryCallback implements FixedFactoryInterface
{
    /**
     * @var callable(array): object
     */
    protected $callback;

    /**
     * @param callable(array $args): object $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function create(array $args = []): object
    {
        return ($this->callback)($args);
    }
}
