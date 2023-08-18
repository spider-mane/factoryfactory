<?php

namespace WebTheory\Factory\Factory;

use WebTheory\Factory\Interfaces\FlexFactoryInterface;

class FlexFactoryCallback implements FlexFactoryInterface
{
    /**
     * @var callable(string, array): object
     */
    protected $callback;

    /**
     * @param callable(string $class, array $args): object $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function create(string $class, array $args = []): object
    {
        return ($this->callback)($class, $args);
    }
}
