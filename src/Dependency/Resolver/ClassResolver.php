<?php

declare(strict_types=1);

namespace ApiCore\Dependency\Resolver;

use ApiCore\Dependency\Hook;
use ApiCore\Dependency\Container;
use ApiCore\Dependency\Handler\HandlerInterface;
use ApiCore\Utils\CollectionInterface;
use ApiCore\Utils\UniqueCollection;
use ReflectionClass;
use ReflectionException;

class ClassResolver
{
    public const METHOD_CONSTRUCT = '__construct';
    private const METHOD_SETTER = 'set';

    private function __construct(
        private readonly Container $container,
        private readonly CollectionInterface $customHandlers
    ) {
        $this->customHandlers
            ->add(new Hook\BeforeConstruct\Handler())
            ->add(new Hook\AfterConstruct\Handler());
    }

    public function resolve(string $className): ?object
    {
        try {
            $reflectionClass = new ReflectionClass($className);

            $instance = null;
            $instance = $this->executeCustomHandlers($instance, $reflectionClass);

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
            $container,
            new UniqueCollection()
        );
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
}
