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
    public function supports(?object $instance, ReflectionClass $reflectionClass): bool
    {
        return $reflectionClass->getName() === SerializerInterface::class;
    }

    public function handle(?object $instance, ReflectionClass $reflectionClass): ?object
    {
        return Container::getInstance()->get(Serializer::class);
    }
}
