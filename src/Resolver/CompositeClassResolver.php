<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\ClassResolverInterface;

class CompositeClassResolver implements ClassResolverInterface
{
    /**
     * @var list<ClassResolverInterface>
     */
    protected array $resolvers = [];

    public function __construct(ClassResolverInterface ...$resolvers)
    {
        $this->resolvers = $resolvers;
    }

    public function getClass(string $arg): string|false
    {
        foreach ($this->resolvers as $resolver) {
            if ($class = $resolver->getClass($arg)) {
                return $class;
            }
        }

        return false;
    }
}
