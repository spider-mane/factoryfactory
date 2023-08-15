<?php

namespace WebTheory\Factory\Interfaces;

interface ArgumentResolverInterface
{
    public function getArgAsParam(string $arg): string;

    public function getParamAsArg(string $param): string;
}
