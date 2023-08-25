<?php

namespace WebTheory\Factory\Exception;

use InvalidArgumentException;
use Throwable;
use WebTheory\Factory\Interfaces\ArgTransformationExceptionInterface;

class UnresolvableItemException extends InvalidArgumentException implements ArgTransformationExceptionInterface
{
    protected string $template = "Unable to resolve for item \"%s.\"";

    public function __construct(string $item, $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf($this->template, $item);

        parent::__construct($message, $code, $previous);
    }
}
