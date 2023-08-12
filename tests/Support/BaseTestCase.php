<?php

declare(strict_types=1);

namespace Tests\Support;

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

    protected function setUp(): void
    {
        parent::setUp();

        $this->initFaker();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->closeMockery();
        $this->tearDownProphecy();
    }
}
