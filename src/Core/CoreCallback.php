<?php

namespace WebTheory\Factory\Core;

use WebTheory\Factory\Interfaces\FlexFactoryCoreInterface;

class CoreCallback implements FlexFactoryCoreInterface
{
    /**
     * @var callable(string, array): (object|false)
     */
    protected $callback;

    /**
     * @param callable(string $query, array $args): (object|false) $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function process(string $query, array $args = []): object|false
    {
        return ($this->callback)($query, $args);
    }
}
