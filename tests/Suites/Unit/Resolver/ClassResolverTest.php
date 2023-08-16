<?php

namespace Tests\Suites\Unit\Resolver;

use Tests\Support\Fixtures\Namespaces\DummyNamespace1\DummyClass1;
use Tests\Support\Fixtures\Namespaces\DummyNamespace1\DummyClass2;
use Tests\Support\Fixtures\Namespaces\DummyNamespace1\DummyClass3;
use Tests\Support\Fixtures\Namespaces\DummyNamespace2\DummyClass4;
use Tests\Support\Fixtures\Namespaces\DummyNamespace2\DummyClass5;
use Tests\Support\Fixtures\Namespaces\DummyNamespace2\DummyClass6;
use Tests\Support\Fixtures\Namespaces\DummyNamespace3\DummyClass7;
use Tests\Support\Fixtures\Namespaces\DummyNamespace3\DummyClass8;
use Tests\Support\Fixtures\Namespaces\DummyNamespace3\DummyClass9;
use Tests\Support\Partials\HasExpectedTypes;
use Tests\Support\UnitTestCase;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Resolver\ClassResolver;

class ClassResolverTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected const DUMMY_CLASSES = [
        DummyClass1::class,
        DummyClass2::class,
        DummyClass3::class,
        DummyClass4::class,
        DummyClass5::class,
        DummyClass6::class,
        DummyClass7::class,
        DummyClass8::class,
        DummyClass9::class,
    ];

    protected const DUMMY_NAMESPACES = [
        'Tests\Support\Fixtures\Namespaces\DummyNamespace1',
        'Tests\Support\Fixtures\Namespaces\DummyNamespace2',
        'Tests\Support\Fixtures\Namespaces\DummyNamespace3',
    ];

    protected ClassResolver $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = $this->createSut([]);
    }

    protected function createSut(array $args): ClassResolver
    {
        return new ClassResolver(
            $args['map'] ?? [],
            $args['classes'] ?? [],
            $args['namespaces'] ?? [],
            $args['convention'] ?? '%s',
        );
    }

    protected function defineExpectedTypesData(callable $ds): array
    {
        return [
            $ds($i = ClassResolverInterface::class) => [$i],
        ];
    }

    /**
     * @test
     * @dataProvider strategicClassResolutionData
     */
    public function it_strategically_resolves_classes(array $args, string $query, string $expect)
    {
        $this->sut = $this->createSut($args);

        $result = $this->sut->getClass($query);

        $this->assertSame($expect, $result);
    }

    public function strategicClassResolutionData(): array
    {
        $ds = fn (string $strategy) => $this->ds()
            ->set('strategy', $strategy)
            ->get();

        return [
            $ds('map') => [
                'args' => [
                    'map' => [
                        ($query = 'dummy') => ($expect = DummyClass1::class),
                    ],
                ],
                'query' => $query,
                'expect' => $expect,
            ],

            $ds('list') => [
                'args' => [
                    'classes' => [
                        DummyClass1::class,
                        $expect = DummyClass2::class,
                        DummyClass3::class,
                    ],
                ],
                'query' => 'dummy_class_2',
                'expect' => $expect,
            ],

            $ds('namespaces') => [
                'args' => [
                    'namespaces' => static::DUMMY_NAMESPACES,
                ],
                'query' => 'dummy_class_8',
                'expect' => DummyClass8::class,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider resolutionByConventionData
     */
    public function it_resolves_classes_by_convention(string $convention, array $args, string $query, string $expect)
    {
        $args['convention'] = $convention;

        $this->sut = $this->createSut($args);

        $result = $this->sut->getClass($query);

        # Smoke
        $this->assertNotSame('%s', $convention);

        $this->assertSame($expect, $result);
    }

    public function resolutionByConventionData(): array
    {
        $classConvention = 'DummyClass%s';

        $ds = fn (string $strategy) => $this->ds()
            ->set('strategy', $strategy)
            ->get();

        return [
            $ds('list') => [
                'convention' => $classConvention,
                'args' => [
                    'classes' => [
                        DummyClass1::class,
                        DummyClass2::class,
                        DummyClass3::class,
                        DummyClass4::class,
                        $expect = DummyClass5::class,
                        DummyClass6::class,
                        DummyClass7::class,
                    ],
                ],
                'query' => '5',
                'expect' => $expect,
            ],

            $ds('namespaces') => [
                'convention' => $classConvention,
                'args' => [
                    'namespaces' => static::DUMMY_NAMESPACES,
                ],
                'query' => '4',
                'expect' => DummyClass4::class,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider invalidClassQueryData
     */
    public function it_returns_false_if_class_query_is_invalid(array $args, string $query)
    {
        $this->sut = $this->createSut($args);

        $result = $this->sut->getClass($query);

        $this->assertFalse($result);
    }

    public function invalidClassQueryData(): array
    {
        $invalidClass = 'InvalidNamespace\AnotherInvalidNamespace\InvalidClass';
        $invalidQuery = 'invalid_class';

        $ds = fn (string $strategy, bool $match) => $this->ds()
            ->set('strategy', $strategy)
            ->set('match', $match)
            ->get();

        return [
            $ds('map', false) => [
                'args' => [
                    'map' => ['dummy' => static::DUMMY_CLASSES[4]],
                ],
                'query' => $invalidQuery,
            ],
            $ds('map', true) => [
                'args' => [
                    'map' => [$invalidQuery => $invalidClass],
                ],
                'query' => $invalidQuery,
            ],

            $ds('list', false) => [
                'args' => [
                    'classes' => static::DUMMY_CLASSES,
                ],
                'query' => $invalidQuery,
            ],
            $ds('list', true) => [
                'args' => [
                    'classes' => [$invalidClass],
                ],
                'query' => $invalidQuery,
            ],

            $ds('namespaces', false) => [
                'args' => [
                    'namespaces' => static::DUMMY_NAMESPACES,
                ],
                'query' => $invalidQuery,
            ],
        ];
    }
}
