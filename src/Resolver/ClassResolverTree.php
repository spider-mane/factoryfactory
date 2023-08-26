<?php

namespace WebTheory\Factory\Resolver;

use ArrayAccess;
use WebTheory\Factory\Exception\UnresolvableEntryException;
use WebTheory\Factory\Exception\UnresolvableQueryException;
use WebTheory\Factory\Exception\UnresolvableSubjectException;
use WebTheory\Factory\Interfaces\ClassArgumentInterface;
use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Interfaces\ClassResolverRepositoryInterface;
use WebTheory\Factory\Interfaces\UniversalDependencyResolverInterface;
use WebTheory\Factory\Transformation\ClassArgument;

class ClassResolverTree implements UniversalDependencyResolverInterface
{
    /**
     * @param array<class-string, ClassResolverRepositoryInterface> $repositories
     */
    public function __construct(protected array|ArrayAccess $repositories)
    {
        //
    }

    public function resolve(string $subject, string $entry, string $query, array $args): ClassArgumentInterface
    {
        return new ClassArgument(
            $this->getClass($subject, $entry, $query),
            $args
        );
    }

    protected function getClass(string $subject, string $entry, string $query): string
    {
        return $this->getResolver($subject, $entry)->getClass($query)
            ?: throw new UnresolvableQueryException($query);
    }

    protected function getResolver(string $class, string $entry): ClassResolverInterface
    {
        return $this->getRepository($class)->getClassResolver($entry)
            ?: throw new UnresolvableEntryException($entry);
    }

    protected function getRepository(string $class): ClassResolverRepositoryInterface
    {
        return $this->repositories[$class]
            ?? throw new UnresolvableSubjectException($class);
    }
}
