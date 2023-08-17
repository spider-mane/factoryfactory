<?php

declare(strict_types=1);

namespace Tests\Support;

use Jawira\CaseConverter\CaseConverter;
use Jawira\CaseConverter\CaseConverterInterface;
use PHPUnit\Framework\TestCase;
use WebTheory\UnitUtils\Concerns\AssertionsTrait;
use WebTheory\UnitUtils\Concerns\FakeGeneratorTrait;
use WebTheory\UnitUtils\Concerns\FormattedDataSetsTrait;
use WebTheory\UnitUtils\Concerns\MockeryTrait;
use WebTheory\UnitUtils\Concerns\ProphecyTrait;
use WebTheory\UnitUtils\Concerns\SystemTrait;

abstract class BaseTestCase extends TestCase
{
    use AssertionsTrait;
    use FakeGeneratorTrait;
    use FormattedDataSetsTrait;
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
