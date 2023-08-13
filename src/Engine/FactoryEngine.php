<?php

namespace WebTheory\Factory\Engine;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use WebTheory\Factory\Abstracts\ConvertsCaseTrait;
use WebTheory\Factory\Interfaces\FactoryEngineInterface;

class FactoryEngine implements FactoryEngineInterface
{
    use ConvertsCaseTrait;

    public const SETTER_PREFIXES = ['set', 'with', ''];

    public function __construct(
        protected array $setterPrefixes = self::SETTER_PREFIXES
    ) {
        //
    }

    public function generate(string $class, array $args = []): object
    {
        $reflection = new ReflectionClass($class);
        $instance = $this->constructInstance($reflection, $args);

        return $this->defineInstance($reflection, $instance, $args);
    }

    protected function constructInstance(ReflectionClass $reflection, array &$args): object
    {
        return $reflection->newInstance(
            ...$this->getConstructorArgs($reflection, $args)
        );
    }

    public function getConstructorArgs(ReflectionClass $reflection, array &$args): array
    {
        $construct = [];
        $keys = $this->convertKeysToParameters($args);
        $constructor = $reflection->getConstructor();
        $params = $constructor ? $constructor->getParameters() : [];

        foreach ($params as $param) {
            if (in_array($name = $param->getName(), $keys)) {
                $arg = $this->convertToArg($name);
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

    protected function convertKeysToParameters(array $args): array
    {
        return array_map(function ($key) {
            return $this->convertToParam($key);
        }, array_keys($args));
    }

    protected function convertToArg(string $param): string
    {
        return $this->convertCase($param)->toSnake();
    }

    protected function convertToParam(string $arg): string
    {
        return $this->convertCase($arg)->toCamel();
    }

    protected function defineInstance(ReflectionClass $reflection, object $instance, array &$args): object
    {
        $setterPrefixes = $this->setterPrefixes;

        foreach ($args as $property => $value) {
            $set = false;

            foreach ($setterPrefixes as $prefix) {
                $setter = $this->convertToSetter($property, $prefix);

                if ($reflection->hasMethod($setter)) {
                    $this->invokeSetterMethod(
                        $reflection->getMethod($setter),
                        $instance,
                        $value
                    );
                    $set = true;

                    break;
                }
            }

            if (!$set) {
                throw new InvalidArgumentException(
                    "{$property} is not a settable property of {$reflection->name}"
                );
            }
        }

        return $instance;
    }

    protected function convertToSetter(string $property, string $prefix = 'set'): string
    {
        $toCase = $prefix ? 'toPascal' : 'toCamel';

        return $prefix . $this->convertCase($property)->$toCase();
    }

    protected function invokeSetterMethod(ReflectionMethod $method, object $instance, mixed $value): void
    {
        $parameter = $method->getParameters()[0];

        if ($parameter->isVariadic() && is_array($value)) {
            $method->invoke($instance, ...$value);
        } else {
            $method->invoke($instance, $value);
        }
    }
}
