<?php

namespace WebTheory\Factory\Exception;

use InvalidArgumentException;
use Throwable;
use WebTheory\Factory\Interfaces\ArgTransformationExceptionInterface;

class UnresolvableQueryException extends InvalidArgumentException implements ArgTransformationExceptionInterface
{
    protected string $template = "Unable to resolve argument \"%s.\"";

    public function __construct(string $query, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf($this->template, $query);

        parent::__construct($message, $code, $previous);
    }
}
