<?php

namespace WebTheory\Factory\Core;

use WebTheory\Factory\Core\Abstracts\AbstractProxyCore;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\FactoryEngineInterface;
use WebTheory\Factory\Interfaces\FixedFactoryRepositoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryCoreInterface;

class FlexFactoryCore extends AbstractProxyCore implements FlexFactoryCoreInterface
{
    public function __construct(
        ClassResolverInterface $resolver,
        FactoryEngineInterface $engine,
        FixedFactoryRepositoryInterface $repository,
        FlexFactoryCoreInterface ...$extra
    ) {
        $this->handler = new CoreComposition(
            new CoreRepository($repository, $resolver),
            new CoreEngine($engine, $resolver),
            ...$extra
        );
    }
}
