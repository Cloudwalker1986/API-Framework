<?php

declare(strict_types=1);

namespace ApiCore\Serializer\Handler;

use ApiCore\Dependency\Container;
use ApiCore\Dependency\Handler\HandlerInterface;
use ApiCore\Serializer\Serializer;
use ApiCore\Serializer\SerializerInterface;
use ReflectionClass;

class SerializerHandler implements HandlerInterface
{
    public function __construct(private readonly Container $container)
    {
    }

    public function supports(?object $instance, ReflectionClass $reflectionClass): bool
    {
        return $reflectionClass->getName() === SerializerInterface::class;
    }

    public function handle(?object $instance, ReflectionClass $reflectionClass): ?object
    {
        return $this->container->get(Serializer::class);
    }
}
