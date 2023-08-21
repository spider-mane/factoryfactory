<?php

namespace WebTheory\Factory\Exception;

use InvalidArgumentException;
use Throwable;

class UnresolvableItemException extends InvalidArgumentException
{
    protected string $template = "Unable to resolve for item \"%s.\"";

    /**
     * @param string $item
     */
    public function __construct(string $item, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf($this->template, $item);

        parent::__construct($message, $code, $previous);
    }
}
