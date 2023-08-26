<?php

namespace WebTheory\Factory\Repository;

use ArrayAccess;
use WebTheory\Factory\Factory\ClassArgumentGenerator;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryRepositoryInterface;

class ArgumentiveFlexFactoryRepository implements FlexFactoryRepositoryInterface
{
    /**
     * @var array<string, FlexFactoryInterface>
     */
    protected array $factories;

    /**
     * @param array<string, ClassResolverInterface|FlexFactoryInterface> $entries
     */
    public function __construct(protected array|ArrayAccess $entries = [])
    {
        //
    }

    public function getTypeFactory(string $type): FlexFactoryInterface|false
    {
        return $this->factories[$type] ??= $this->getEntry($type);
    }

    protected function getEntry(string $type): FlexFactoryInterface|false
    {
        if (!$entry = $this->entries[$type] ?? false) {
            return false;
        }

        return $this->resolveEntry($entry);
    }

    protected function resolveEntry(ClassResolverInterface|FlexFactoryInterface $entry): FlexFactoryInterface
    {
        return $entry instanceof ClassResolverInterface
            ? new ClassArgumentGenerator($entry)
            : $entry;
    }
}
