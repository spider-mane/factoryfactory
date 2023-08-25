<?php

namespace WebTheory\Factory\Exception;

use InvalidArgumentException;
use Throwable;
use WebTheory\Factory\Interfaces\ArgTransformationExceptionInterface;

class UnresolvableSubjectException extends InvalidArgumentException implements ArgTransformationExceptionInterface
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
