<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Abstracts\ConvertsCaseTrait;
use WebTheory\Factory\Interfaces\ArgumentResolverInterface;

class SnakeArgsCamelParams implements ArgumentResolverInterface
{
    use ConvertsCaseTrait;

    public function getArgAsParam(string $arg): string
    {
        return $this->convertCase($arg)->toCamel();
    }

    public function getParamAsArg(string $param): string
    {
        return $this->convertCase($param)->toSnake();
    }
}
