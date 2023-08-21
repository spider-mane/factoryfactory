<?php

namespace WebTheory\Factory\Exception;

use LogicException;
use Throwable;

class UnresolvableSetterException extends LogicException
{
    protected string $template = "Unable to set value for item \"%s\" on class \"%s.\"";

    /**
     * @param string $item
     */
    public function __construct(string $class, string $item, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf($this->template, $item, $class);

        parent::__construct($message, $code, $previous);
    }
}
