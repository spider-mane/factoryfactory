<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Resolver\Abstracts\AbstractProxyClassResolver;

class ClassResolver extends AbstractProxyClassResolver implements ClassResolverInterface
{
    public function __construct(
        array $map = [],
        array $classes = [],
        array $namespaces = [],
        ?string $convention = null
    ) {
        $this->handler = new CompositeClassResolver(
            new MappedClasses($map),
            new ClassList($classes, $convention),
            new Namespaces($namespaces, $convention)
        );
    }
}
