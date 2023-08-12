<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Resolver\Abstracts\DynamicClassResolverTrait;

class Namespaces implements ClassResolverInterface
{
    use DynamicClassResolverTrait;

    /**
     * @param list<class-string> $namespaces
     * @param string $convention
     */
    public function __construct(
        protected array $namespaces,
        protected string $convention = '%s'
    ) {
        //
    }

    public function getClass(string $arg): string|false
    {
        foreach ($this->namespaces as $namespace) {
            $class = $this->getFullyQualifiedName($namespace, $arg);

            if (class_exists($class)) {
                return $class;
            }
        }

        return false;
    }

    protected function getClassConvention(): string
    {
        return $this->convention;
    }
}
