<?php

namespace WebTheory\Factory\Resolver\Abstracts;

use WebTheory\Factory\Abstracts\ConvertsCaseTrait;

trait DynamicClassResolverTrait
{
    use ConvertsCaseTrait;

    protected ?string $convention;

    protected function getUqn(string $class): string
    {
        $parts = explode('\\', $class);

        return end($parts);
    }

    protected function getFqn(string ...$parts): string
    {
        return implode('\\', $parts);
    }

    protected function getArgAsQualifiedClass(string $namespace, string $arg): string
    {
        return $this->getFqn($namespace, $this->getArgAsClass($arg));
    }

    protected function getArgAsClass(string $arg): string
    {
        $class = $this->convertArgToClassFormat($arg);

        if ($convention = $this->convention) {
            $class = sprintf($convention, $class);
        }

        return $class;
    }

    protected function convertArgToClassFormat(string $arg): string
    {
        return $this->convertCase($arg)->toPascal();
    }
}
