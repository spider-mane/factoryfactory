<?php

namespace WebTheory\Factory\Interfaces;

use ReflectionClass;

interface SetterResolverInterface
{
    /**
     * @param string|object|ReflectionClass $class
     */
    public function getSetter(string|object $class, string $arg): string|false;
}
