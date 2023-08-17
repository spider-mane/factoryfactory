<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Resolver\Abstracts\AbstractClassResolver;
use WebTheory\Factory\Resolver\Abstracts\DynamicClassResolverTrait;

class Namespaces extends AbstractClassResolver implements ClassResolverInterface
{
    use DynamicClassResolverTrait;

    /**
     * @param list<class-string> $namespaces
     * @param string $convention
     */
    public function __construct(
        protected array $namespaces,
        protected ?string $convention = null
    ) {
        //
    }

    protected function performQuery(string $arg): string|false
    {
        foreach ($this->namespaces as $namespace) {
            $class = $this->getArgAsQualifiedClass($namespace, $arg);

            if (class_exists($class)) {
                return $class;
            }
        }

        return false;
    }
}
