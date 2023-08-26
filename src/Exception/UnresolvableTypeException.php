<?php

namespace WebTheory\Factory\Exception;

use InvalidArgumentException;
use Throwable;
use WebTheory\Factory\Interfaces\ArgTransformationExceptionInterface;

class UnresolvableTypeException extends InvalidArgumentException implements ArgTransformationExceptionInterface
{
    protected string $template = "Unable to resolve for type \"%s\"";

    public function __construct(string $type, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf($this->template, $type);

        parent::__construct($message, $code, $previous);
    }
}
