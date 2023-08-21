<?php

declare(strict_types=1);

namespace WebTheory\Factory\Engine;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use WebTheory\Factory\Exception\UnresolvableConstructorArgumentException;
use WebTheory\Factory\Exception\UnresolvableSetterException;
use WebTheory\Factory\Interfaces\ArgumentResolverInterface;
use WebTheory\Factory\Interfaces\ArgValueTransformerInterface;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;
use WebTheory\Factory\Interfaces\FactoryEngineInterface;
use WebTheory\Factory\Interfaces\SetterResolverInterface;
use WebTheory\Factory\Resolver\CamelCaseSetterResolver;
use WebTheory\Factory\Resolver\SnakeArgsCamelParams;
use WebTheory\Factory\Transformation\OriginalValue;

class FactoryEngine implements FactoryEngineInterface
{
    protected SetterResolverInterface $setterResolver;

    protected ArgumentResolverInterface $argResolver;

    protected ?ArgValueTransformerInterface $valueTransformer;

    public function __construct(
        ?SetterResolverInterface $setterResolver = null,
        ?ArgumentResolverInterface $argResolver = null,
        ?ArgValueTransformerInterface $valueTransformer = null,
    ) {
        $this->setterResolver = $setterResolver ?? new CamelCaseSetterResolver();
        $this->argResolver = $argResolver ?? new SnakeArgsCamelParams();
        $this->valueTransformer = $valueTransformer ?? new OriginalValue();
    }

    public function generate(string $class, array $args = []): object
    {
        $reflection = new ReflectionClass($class);
        $instance = $this->constructInstance($reflection, $args);

        return $this->refineInstance($reflection, $instance, $args);
    }

    protected function constructInstance(ReflectionClass $class, array &$args): object
    {
        $with = ($constructor = $class->getConstructor())
            ? $this->getConstructorArgs($constructor, $args)
            : [];

        return $class->newInstance(...$with);
    }

    protected function getConstructorArgs(ReflectionMethod $constructor, array &$args): array
    {
        $keys = $this->convertKeysToParameters($args);

        $extracted = [];

        foreach ($constructor->getParameters() as $param) {
            $arg = $this->convertParamToArg($name = $param->getName());

            if (in_array($name, $keys)) {
                $extracted[] = $this->parseArg($arg, $args[$arg], $param);

                unset($args[$arg]);
            } else {
                try {
                    $extracted[] = $param->getDefaultValue();
                } catch (ReflectionException $e) {
                    throw $this->unresolvableConstructorArgumentException($arg);
                }
            }
        }

        $lastArg = array_pop($extracted);

        return $this->isVariadicArgument($constructor, $lastArg)
            ? [...$extracted, ...$lastArg]
            : [...$extracted, $lastArg];
    }

    protected function convertKeysToParameters(array $args): array
    {
        return array_map([$this, 'convertArgToParam'], array_keys($args));
    }

    protected function convertArgToParam(string $arg): string
    {
        return $this->argResolver->getArgAsParam($arg);
    }

    protected function convertParamToArg(string $param): string
    {
        return $this->argResolver->getParamAsArg($param);
    }

    protected function parseArg(string $name, mixed $value, ReflectionParameter $param): mixed
    {
        $value = $this->valueTransformer->transformArg($name, $value, $param);

        return $value instanceof ClassArgumentInterface
            ? $this->generate($value->getClass(), $value->getArgs())
            : $value;
    }

    protected function unresolvableConstructorArgumentException(string $arg): UnresolvableConstructorArgumentException
    {
        return new UnresolvableConstructorArgumentException($arg);
    }

    protected function isVariadicArgument(ReflectionMethod $method, mixed $arg): bool
    {
        if (!$method->isVariadic() || !is_array($arg)) {
            return false;
        }

        $params = $method->getParameters();

        // @phpstan-ignore-next-line
        if ('array' === end($params)->getType()?->getName()) {
            foreach ($arg as $entry) {
                if (!is_array($entry)) {
                    return false;
                }
            }
        }

        return true;
    }

    protected function refineInstance(ReflectionClass $class, object $instance, array &$args): object
    {
        foreach ($args as $property => $value) {
            if ($setter = $this->resolveSetter($class, $property)) {
                $reflection = $class->getMethod($setter);
                $params = $reflection->getParameters();
                $arg = $this->parseArg($property, $value, reset($params));

                $this->invokeSetterMethod($reflection, $instance, $arg);
            } else {
                throw $this->unresolvableArgException($class, $property);
            }
        }

        return $instance;
    }

    protected function resolveSetter(ReflectionClass $class, string $property): string|false
    {
        return $this->setterResolver->getSetter($class, $property);
    }

    protected function invokeSetterMethod(ReflectionMethod $setter, object $instance, mixed $value): void
    {
        if ($this->isVariadicArgument($setter, $value)) {
            $setter->invoke($instance, ...$value);
        } else {
            $setter->invoke($instance, $value);
        }
    }

    protected function unresolvableArgException(ReflectionClass $class, string $property): UnresolvableSetterException
    {
        return new UnresolvableSetterException($class->getName(), $property);
    }
}
