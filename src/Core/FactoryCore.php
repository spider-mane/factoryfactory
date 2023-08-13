<?php

namespace WebTheory\Factory\Core;

use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\FactoryCoreInterface;
use WebTheory\Factory\Interfaces\FactoryEngineInterface;

class FactoryCore implements FactoryCoreInterface
{
    public function __construct(
        protected ClassResolverInterface $resolver,
        protected FactoryEngineInterface $engine = null
    ) {
        //
    }

    public function produce(string $class, array $args = []): object|false
    {
        $result = $this->resolver->getClass($class);

        return $result ? $this->engine->generate($result, $args) : $result;
    }
}
