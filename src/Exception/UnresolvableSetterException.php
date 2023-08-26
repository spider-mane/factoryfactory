<?php

namespace WebTheory\Factory\Exception;

use LogicException;
use Throwable;

class UnresolvableSetterException extends LogicException
{
    protected string $template = "Unable to set value for entry \"%s\" on class \"%s.\"";

    /**
     * @param string $entry
     */
    public function __construct(string $class, string $entry, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf($this->template, $entry, $class);

        parent::__construct($message, $code, $previous);
    }
}
