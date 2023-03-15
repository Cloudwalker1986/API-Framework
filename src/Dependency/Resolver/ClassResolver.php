<?php

declare(strict_types=1);

namespace ApiCore\Dependency\Resolver;

use ApiCore\Dependency\Container;
use ReflectionClass;
use ReflectionException;

class ClassResolver
{
    public const METHOD_CONSTRUCT = '__construct';
    private const METHOD_SETTER = 'set';

    private function __construct(
        private readonly Container $container
    ) {
    }

    public function resolve(string $className): ?object
    {
        try {
            $reflectionClass = new ReflectionClass($className);

            if ($reflectionClass->hasMethod(self::METHOD_CONSTRUCT)) {
                return $this->resolveByConstruct($reflectionClass);
            }

            return $this->hydrateBySetters($reflectionClass);
        } catch (ReflectionException $e) {
            //@todo add logging here
            return null;
        }
    }

    /**
     * @throws ReflectionException
     */
    private function resolveByConstruct(ReflectionClass $reflectionClass): ?object
    {
        $arguments = [];

        foreach ($reflectionClass->getConstructor()?->getParameters() as $parameter) {
            $arguments[] = $this->container->get($parameter->getType()?->getName());
        }

        return $reflectionClass->newInstanceArgs($arguments);
    }

    /**
     * @throws ReflectionException
     */
    private function hydrateBySetters(ReflectionClass $reflectionClass): ?object
    {
        $instance = $reflectionClass->newInstance();
        foreach ($reflectionClass->getMethods() as $reflectionMethod) {
            $arguments = [];
            if (str_starts_with($reflectionMethod->getName(), self::METHOD_SETTER)) {
                $method = $reflectionMethod->getName();
                foreach ($reflectionMethod->getParameters() as $parameter) {
                    $arguments[] = $this->container->get($parameter->getType()?->getName());
                }
                call_user_func_array([$instance, $method], $arguments);
            }
        }
        return $instance;
    }

    public static function build(Container $container): ClassResolver
    {
        return new static(
            $container
        );
    }
}
