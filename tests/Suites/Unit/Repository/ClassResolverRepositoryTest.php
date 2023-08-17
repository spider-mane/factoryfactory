<?php

namespace Tests\Suites\Unit\Repository;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\ClassResolverRepositoryInterface;
use WebTheory\Factory\Repository\ClassResolverRepository;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

class ClassResolverRepositoryTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected ClassResolverRepository $sut;

    /**
     * @var MockObject&ClassResolverInterface
     */
    protected ClassResolverInterface $resolver;

    protected string $key;

    protected function setUp(): void
    {
        parent::setUp();

        $this->key = $this->dummyArg();
        $this->resolver = $this->createMock(ClassResolverInterface::class);

        $this->sut = new ClassResolverRepository([
            $this->key => $this->resolver,
        ]);
    }

    protected function defineExpectedTypesData(callable $ds): array
    {
        return [
            $ds($i = ClassResolverRepositoryInterface::class) => [$i],
        ];
    }

    /**
     * @test
     */
    public function it_returns_requested_resolver()
    {
        $result = $this->sut->getClassResolver($this->key);

        $this->assertSame($this->resolver, $result);
    }

    /**
     * @test
     */
    public function it_returns_false_if_it_does_not_have_mapped_resolver()
    {
        $this->assertFalse($this->sut->getClassResolver('invalid_resolver'));
    }
}
