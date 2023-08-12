<?php

namespace WebTheory\Factory\Resolver\Abstracts;

use WebTheory\Factory\Abstracts\ConvertsCaseTrait;

trait DynamicClassResolverTrait
{
    use ConvertsCaseTrait;

    protected function getUnqualifiedName(string $class): string
    {
        $parts = explode('\\', $class);

        return end($parts);
    }

    protected function getFullyQualifiedName(string $namespace, string $class): string
    {
        return $namespace . '\\' . $this->getClassName($class);
    }

    protected function getClassName(string $class): string
    {
        $class = $this->convertToClass($class);

        if ($convention = $this->getClassConvention()) {
            $class = sprintf($convention, $class);
        }

        return $class;
    }

    protected function convertToClass(string $class): string
    {
        return $this->convertCase($class)->toPascal();
    }

    protected function getClassConvention(): string
    {
        return '%s';
    }
}
