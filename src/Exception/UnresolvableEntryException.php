<?php

namespace WebTheory\Factory\Exception;

use InvalidArgumentException;
use Throwable;
use WebTheory\Factory\Interfaces\ArgTransformationExceptionInterface;

class UnresolvableEntryException extends InvalidArgumentException implements ArgTransformationExceptionInterface
{
    protected string $template = "Unable to resolve for entry \"%s.\"";

    public function __construct(string $entry, $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf($this->template, $entry);

        parent::__construct($message, $code, $previous);
    }
}
