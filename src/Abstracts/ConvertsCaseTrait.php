<?php

namespace WebTheory\Factory\Abstracts;

use Jawira\CaseConverter\CaseConverter;
use Jawira\CaseConverter\CaseConverterInterface;
use Jawira\CaseConverter\Convert;

trait ConvertsCaseTrait
{
    protected CaseConverterInterface $caseConverter;

    protected function getCaseConverter(): CaseConverterInterface
    {
        return $this->caseConverter ??= new CaseConverter();
    }

    protected function convertCase(string $source): Convert
    {
        return $this->getCaseConverter()->convert($source);
    }
}
