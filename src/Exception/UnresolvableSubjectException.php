<?php

namespace WebTheory\Factory\Exception;

use InvalidArgumentException;
use Throwable;

class UnresolvableSubjectException extends InvalidArgumentException
{
    protected string $template = "Unable to resolve arguments on class \"%s.\"";

    /**
     * @param class-string $class
     */
    public function __construct(string $class, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf($this->template, $class);

        parent::__construct($message, $code, $previous);
    }
}
