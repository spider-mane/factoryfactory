<?php

namespace WebTheory\Factory\Traits;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use WebTheory\Factory\SwitchCase;
use WebTheory\Factory\Interfaces\FactoryRepositoryInterface;

trait SmartFactoryTrait
{
    protected $repository;

    public function __construct(FactoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    protected function getFactory(string $interface)
    {
        return $this->repository->getFactoryFor($this->getClassName());
    }

    /**
     * @param string $class
     * @param array $args
     * @return object
     * @throws \ReflectionException
     */
    protected function build(string $class, array $args)
    {
        $reflection = new ReflectionClass($class);
        $instance = $this->constructInstance($reflection, $args);

        return $this->defineInstance($reflection, $instance, $args);
    }

    /**
     * @param ReflectionClass $reflection
     * @param array $args
     * @return object
     */
    protected function constructInstance(ReflectionClass $reflection, array &$args): object
    {
        return $reflection->newInstance(...$this->getConstructorArgs($reflection, $args));
    }

    /**
     * @param ReflectionClass $reflection
     * @param object $instance
     * @param array $args
     * @return object
     * @throws \ReflectionException
     */
    protected function defineInstance(ReflectionClass $reflection, object $instance, array &$args): object
    {
        $prefixes = ['set', 'with'];

        foreach ($args as $property => $value) {
            $set = false;

            foreach ($prefixes as $prefix) {
                $setter = $this->getSetter($property, $prefix);

                if ($reflection->hasMethod($setter)) {
                    $this->invokeMethod($reflection->getMethod($setter), $instance, $value);
                    $set = true;
                    break;
                }
            }

            if (!$set) {
                throw new InvalidArgumentException("{$property} is not a settable property of {$reflection->name}");
            }
        }

        return $instance;
    }

    /**
     * @param ReflectionClass $reflection
     * @param array $args
     * @return array
     */
    public function getConstructorArgs(ReflectionClass $reflection, array &$args): array
    {
        $construct = [];
        $keys = $this->getKeysAsParameters($args);
        $constructor = $reflection->getConstructor();
        $params = $constructor->getParameters();

        foreach ($params as $param) {

            if (in_array($param->name, $keys)) {
                $arg = $this->getArg($param->name);
                $construct[] = $args[$arg];

                unset($args[$arg]);
            } else {
                try {
                    $construct[] = $param->getDefaultValue();
                } catch (ReflectionException $message) {
                    throw new InvalidArgumentException($message);
                }
            }
        }

        return $construct;
    }

    /**
     * @param ReflectionMethod $method
     * @param object $instance
     * @param $value
     */
    protected function invokeMethod(ReflectionMethod $method, object $instance, $value)
    {
        $parameter = $method->getParameters()[0];
        $type = $parameter->getType();

        if (interface_exists($type)) {
            $value = $this->resolveArg($type, $value);
        }

        if ($parameter->isVariadic() && is_array($value)) {
            $method->invoke($instance, ...$value);
        } else {
            $method->invoke($instance, $value);
        }
    }

    protected function resolveArg(string $type, $value)
    {
        $factory = $this->repository->getFactoryFor($type);

        $thing = $value['@create'];

        $factory->create($thing, $value);
    }

    /**
     * @param array $args
     * @return array
     */
    protected function getKeysAsParameters(array $args): array
    {
        return array_map(function ($key) {
            return $this->getParam($key);
        }, array_keys($args));
    }

    /**
     * @param string $property
     * @param string $prefix
     * @return string
     */
    public function getSetter(string $property, string $prefix = 'set'): string
    {
        return $prefix . SwitchCase::studly($property);
    }

    /**
     * @param string $param
     * @return string
     */
    public function getArg(string $param): string
    {
        return SwitchCase::snake($param);
    }

    /**
     * @param string $arg
     * @return string
     */
    public function getParam(string $arg): string
    {
        return SwitchCase::camel($arg);
    }
}
