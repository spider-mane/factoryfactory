<?php

namespace WebTheory\Factory\Exception;

use LogicException;
use Throwable;

class UnresolvableConstructorArgumentException extends LogicException
{
    protected string $template = "No default value specified for \"%s.\"";

    /**
     * @param string $arg
     */
    public function __construct(string $arg, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf($this->template, $arg);

        parent::__construct($message, $code, $previous);
    }
}
