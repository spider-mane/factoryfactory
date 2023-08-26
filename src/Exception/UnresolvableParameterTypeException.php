<?php

namespace WebTheory\Factory\Exception;

use LogicException;
use ReflectionParameter;
use Throwable;
use WebTheory\Factory\Interfaces\ArgTransformationExceptionInterface;

class UnresolvableParameterTypeException extends LogicException implements ArgTransformationExceptionInterface
{
    protected string $template = "Parameter \"%s\" has unresolvable type.";

    public function __construct(string|ReflectionParameter $param, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf(
            $this->template,
            $param instanceof ReflectionParameter ? $param->getName() : $param
        );

        parent::__construct($message, $code, $previous);
    }
}
