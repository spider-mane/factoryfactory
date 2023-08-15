<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Resolver\Abstracts\AbstractClassResolver;

class MappedClasses extends AbstractClassResolver implements ClassResolverInterface
{
    /**
     * @param array<string, class-string> $map
     */
    public function __construct(protected array $map)
    {
        //
    }

    protected function performQuery(string $arg): string|false
    {
        if ($this->map && class_exists($class = $this->map[$arg] ?? '')) {
            return $class;
        }

        return false;
    }
}
