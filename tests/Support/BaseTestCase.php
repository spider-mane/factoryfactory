<?php

declare(strict_types=1);

namespace Tests\Support;

use Jawira\CaseConverter\CaseConverter;
use Jawira\CaseConverter\CaseConverterInterface;
use PHPUnit\Framework\TestCase;
use Tests\Support\Concerns\AssertionsTrait;
use Tests\Support\Concerns\FakeGeneratorTrait;
use Tests\Support\Concerns\MockeryTrait;
use Tests\Support\Concerns\ProphecyTrait;
use Tests\Support\Concerns\SystemTrait;

abstract class BaseTestCase extends TestCase
{
    use AssertionsTrait;
    use FakeGeneratorTrait;
    use MockeryTrait;
    use ProphecyTrait;
    use SystemTrait;

    protected CaseConverterInterface $caseConverter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->init();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->closeMockery();
        $this->tearDownProphecy();
    }

    protected function init(): void
    {
        $this->initFaker();
        $this->initCaseConverter();
    }

    protected function initCaseConverter(): void
    {
        $this->caseConverter = new CaseConverter();
    }

    protected function dummyArg(): string
    {
        return $this->caseConverter->convert($this->fake->words(3, true))
            ->toSnake();
    }
}
