<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Resolver\Abstracts\DynamicClassResolverTrait;

class ClassList implements ClassResolverInterface
{
    use DynamicClassResolverTrait;

    /**
     * @param list<class-string> $classes
     * @param string $convention
     */
    public function __construct(
        protected array $classes,
        protected string $convention = '%s'
    ) {
        //
    }

    public function getClass(string $arg): string|false
    {
        foreach ($this->classes as $class) {
            if ($this->isMatchingClass($class, $arg) && class_exists($class)) {
                return $class;
            }
        }

        return false;
    }

    protected function isMatchingClass(string $class, string $arg): bool
    {
        return $this->getUnqualifiedName($class) === $this->getClassName($arg);
    }

    protected function getClassConvention(): string
    {
        return $this->convention;
    }
}
