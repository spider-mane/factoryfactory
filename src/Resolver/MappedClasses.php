<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\ClassResolverInterface;

class MappedClasses implements ClassResolverInterface
{
    /**
     * @param array<string, class-string> $map
     */
    public function __construct(protected array $map)
    {
        //
    }

    public function getClass(string $arg): string|false
    {
        if ($this->map && class_exists($class = $this->map[$arg] ?? '')) {
            return $class;
        }

        return false;
    }
}
