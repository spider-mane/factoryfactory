<?php

namespace WebTheory\Factory\Interfaces;

interface ClassResolverInterface
{
    /**
     * @return class-string|false
     */
    public function getClass(string $arg): string|false;
}
