<?php

namespace WebTheory\Factory\Exception;

use InvalidArgumentException;
use Throwable;

class UnresolvableQueryException extends InvalidArgumentException
{
    protected string $template = "Unable to resolve argument \"%s.\"";

    /**
     * @param string $query
     */
    public function __construct(string $query, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf($this->template, $query);

        parent::__construct($message, $code, $previous);
    }
}
