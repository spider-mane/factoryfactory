<?php

namespace WebTheory\Factory\Resolver;

use WebTheory\Factory\Interfaces\ClassResolverInterface;
use WebTheory\Factory\Resolver\Abstracts\AbstractClassResolver;

class CompositeClassResolver extends AbstractClassResolver implements ClassResolverInterface
{
    /**
     * @var list<ClassResolverInterface>
     */
    protected array $resolvers = [];

    public function __construct(ClassResolverInterface ...$resolvers)
    {
        $this->resolvers = $resolvers;
    }

    protected function performQuery(string $arg): string|false
    {
        foreach ($this->resolvers as $resolver) {
            if ($class = $resolver->getClass($arg)) {
                return $class;
            }
        }

        return false;
    }
}
