<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Abstracts\ConvertsCaseTrait;
use WebTheory\Factory\Interfaces\SetterResolverInterface;
use WebTheory\Factory\Resolver\Abstracts\ValidatesSetterTrait;
use WebTheory\Factory\Utils\ClassUtils;

class CamelCaseSetterResolver implements SetterResolverInterface
{
    use ConvertsCaseTrait;
    use ValidatesSetterTrait;

    public const FORMATS = ['set%s', 'with%s', '%s'];

    public function __construct(protected $formats = self::FORMATS)
    {
        $this->classUtils = new ClassUtils();
    }

    public function getSetter(string|object $class, string $arg): string|false
    {
        foreach ($this->formats as $format) {
            $setter = $this->convertArgToSetter($arg, $format);

            if ($this->isValidSetter($class, $setter)) {
                return $setter;
            }
        }

        return false;
    }

    protected function convertArgToSetter(string $arg, string $format): string
    {
        $strategy = $this->getConversionStrategy($format);

        return sprintf($format, $this->convertCase($arg)->$strategy());
    }

    protected function getConversionStrategy(string $format): string
    {
        return str_starts_with($format, '%s') ? 'toCamel' : 'toPascal';
    }
}
