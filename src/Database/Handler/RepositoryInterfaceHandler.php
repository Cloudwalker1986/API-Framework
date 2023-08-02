<?php

declare(strict_types=1);

namespace ApiCore\Database\Handler;

use ApiCore\Database\Attribute\Repository;
use ApiCore\Database\Generator\ClassGenerator;
use ApiCore\Dependency\Container;
use ApiCore\Dependency\Handler\HandlerInterface;
use ReflectionClass;

class RepositoryInterfaceHandler implements HandlerInterface
{
    public function __construct(
        private readonly ClassGenerator $classGenerator,
        private readonly Container $container
    ) {
    }

    public function supports(?object $instance, ReflectionClass $reflectionClass): bool
    {
        return !empty($reflectionClass->getAttributes(Repository::class)[0]);
    }

    public function handle(?object $instance, ReflectionClass $reflectionClass): ?object
    {
        $className = $this->classGenerator->generate($instance, $reflectionClass);

        return $this->container->get($className);
    }
}
