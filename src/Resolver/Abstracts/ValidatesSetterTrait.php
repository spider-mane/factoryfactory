<?php

namespace WebTheory\Factory\Resolver\Abstracts;

use ReflectionClass;
use WebTheory\Factory\Utils\ClassUtils;

trait ValidatesSetterTrait
{
    protected ClassUtils $classUtils;

    /**
     * @param string|object|ReflectionClass $class
     */
    protected function isValidSetter(string|object $class, string $setter): bool
    {
        return $this->classUtils->methodExists($class, $setter);
    }
}
