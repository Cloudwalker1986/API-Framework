<?php

declare(strict_types=1);

namespace ApiCore\Dependency\Resolver;

use ApiCore\Config;
use ApiCore\Dependency\Attribute\InterfaceTag;
use ApiCore\Dependency\Hook;
use ApiCore\Dependency\Container;
use ApiCore\Dependency\Handler\HandlerInterface;
use ApiCore\Serializer\Handler\NormalizerHandler;
use ApiCore\Serializer\Handler\SerializerHandler;
use ApiCore\Utils\CollectionInterface;
use ApiCore\Utils\Handler\CollectionHandler;
use ApiCore\Utils\Handler\MapHandler;
use ApiCore\Utils\HashMap;
use ApiCore\Utils\UniqueCollection;
use ReflectionClass;
use ReflectionException;

class ClassResolver
{
    public const METHOD_CONSTRUCT = '__construct';
    public const METHOD_SETTER = 'set';

    private function __construct(
        private readonly Container $container,
        private readonly CollectionInterface $customHandlers,
        private readonly CollectionInterface $interfaceHandlers,
        private readonly InterfaceResolver $interfaceResolver
    ) {
        $this->customHandlers
            ->add(new Hook\BeforeConstruct\Handler())
            ->add(new Config\Handler())
            ->add(new Hook\AfterConstruct\Handler());

        $this->interfaceHandlers
            ->add(new SerializerHandler())
            ->add(new NormalizerHandler())
            ->add(new MapHandler())
            ->add(new CollectionHandler());
    }

    public function resolve(string $className): ?object
    {
        try {
            $reflectionClass = new ReflectionClass($className);

            $instance = null;

            if ($reflectionClass->isInterface()) {
                $instance = $this->runInterfaceHandler($instance, $reflectionClass);
            } else {
                $instance = $this->executeCustomHandlers($instance, $reflectionClass);
            }

            return $instance ?? $this->executeCustomHandlers(
                $this->hydrateInstance($reflectionClass),
                $reflectionClass
            );

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

            $name = $parameter->getType()?->getName();

            if (interface_exists($name)) {
                $resolvedName = $this->container->get($name);
                if ($resolvedName === null) {
                    $resolvedName = $this->interfaceResolver->resolve($parameter);
                }
            } else {
                $resolvedName = $this->container->get($name);
            }

            $arguments[$parameter->getPosition()] = is_string($resolvedName)
                ? $this->container->get($resolvedName)
                : $resolvedName;
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

    /**
     * @param object|null $instance
     * @return object|null
     */
    protected function executeCustomHandlers(?object $instance, ReflectionClass $reflectionClass): ?object
    {
        /** @var HandlerInterface $handler */
        while ($this->customHandlers->valid()) {
            $handler = $this->customHandlers->current();
            if ($handler->supports($instance, $reflectionClass)) {
                $instance = $handler->handle($instance, $reflectionClass);
            }
            $this->customHandlers->next();
        }

        $this->customHandlers->rewind();

        return $instance;
    }

    /**
     * @throws ReflectionException
     */
    private function hydrateInstance(ReflectionClass $reflectionClass): ?object
    {
        if ($reflectionClass->hasMethod(self::METHOD_CONSTRUCT)) {
            return $this->resolveByConstruct($reflectionClass);
        }

        return $this->hydrateBySetters($reflectionClass);
    }

    private function runInterfaceHandler(?object $instance, ReflectionClass $reflectionClass): ?object
    {
        while ($this->interfaceHandlers->valid()) {
            $handler = $this->interfaceHandlers->current();
            if ($handler->supports($instance, $reflectionClass)) {
                $instance = $handler->handle($instance, $reflectionClass);
            }
            $this->interfaceHandlers->next();
        }

        $this->interfaceHandlers->rewind();

        return $instance;
    }

    public static function build(Container $container): ClassResolver
    {
        return new static(
            $container,
            new UniqueCollection(),
            new UniqueCollection(),
            new InterfaceResolver()
        );
    }
}
