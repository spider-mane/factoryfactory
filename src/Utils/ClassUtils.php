<?php

namespace WebTheory\Factory\Utils;

use ReflectionClass;

class ClassUtils
{
    /**
     * @param string|object|ReflectionClass $class
     */
    public function methodExists(string|object $class, string $setter): bool
    {
        return ($class instanceof ReflectionClass && $class->hasMethod($setter))
            || method_exists($class, $setter);
    }
}
