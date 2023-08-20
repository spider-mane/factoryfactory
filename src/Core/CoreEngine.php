<?php

namespace WebTheory\Factory\Core;

use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\FactoryEngineInterface;
use WebTheory\Factory\Interfaces\FlexFactoryCoreInterface;

class CoreEngine implements FlexFactoryCoreInterface
{
    public function __construct(
        protected FactoryEngineInterface $engine,
        protected ClassResolverInterface $resolver,
    ) {
        //
    }

    public function process(string $query, array $args = []): object|false
    {
        if (!$class = $this->resolver->getClass($query)) {
            return false;
        }

        return $this->engine->generate($class, $args);
    }
}
