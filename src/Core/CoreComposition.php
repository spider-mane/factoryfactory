<?php

namespace WebTheory\Factory\Core;

use WebTheory\Factory\Interfaces\FlexFactoryCoreInterface;

class CoreComposition implements FlexFactoryCoreInterface
{
    /**
     * @var list<FlexFactoryCoreInterface>
     */
    protected iterable $cores;

    public function __construct(FlexFactoryCoreInterface ...$cores)
    {
        $this->cores = $cores;
    }

    public function process(string $query, array $args = []): object|false
    {
        foreach ($this->cores as $core) {
            if ($result = $core->process($query, $args)) {
                return $result;
            }
        }

        return false;
    }
}
