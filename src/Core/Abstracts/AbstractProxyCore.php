<?php

namespace WebTheory\Factory\Core\Abstracts;

use WebTheory\Factory\Interfaces\FlexFactoryCoreInterface;

abstract class AbstractProxyCore implements FlexFactoryCoreInterface
{
    protected FlexFactoryCoreInterface $handler;

    public function process(string $query, array $args = []): object|false
    {
        return $this->handler->process($query, $args);
    }
}
