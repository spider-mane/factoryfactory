<?php

namespace WebTheory\Factory\Traits;

use WebTheory\Factory\SwitchCase;

trait ClassResolverTrait
{
    /**
     * Get the value of namespace
     *
     * @return mixed
     */
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }

    /**
     * Set the value of namespace
     *
     * @param mixed $namespace
     *
     * @return self
     */
    public function addNamespace(string $namespace)
    {
        $this->namespaces[] = $namespace;

        return $this;
    }

    /**
     * @param array $namespaces
     * @return ClassResolverTrait
     */
    public function addNamespaces(array $namespaces)
    {
        $this->namespaces = $namespaces + $this->namespaces;

        return $this;
    }

    /**
     * @param string $class
     * @return string
     */
    protected function getClassName(string $class)
    {
        $class = SwitchCase::studly($class);

        if ($this->getClassConvention()) {
            $class = sprintf($this->getClassConvention(), $class);
        }

        return $class;
    }

    /**
     * @param string $namespace
     * @param string $class
     * @return string
     */
    protected function getFqn(string $namespace, string $class)
    {
        return $namespace . '\\' . $this->getClassName($class);
    }

    /**
     * @param string $arg
     * @return bool|string
     */
    protected function getClass(string $arg)
    {
        foreach ($this->namespaces as $namespace) {

            $class = $this->getFqn($namespace, $arg);

            if (class_exists($class)) {
                return $class;
            }
        }

        return false;
    }

    protected function getClassConvention(): ?string
    {
        return null;
    }
}
