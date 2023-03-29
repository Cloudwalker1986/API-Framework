<?php

declare(strict_types=1);

namespace ApiCore\Serializer\Handler;

use ApiCore\Dependency\Container;
use ApiCore\Dependency\Handler\HandlerInterface;
use ApiCore\Serializer\Normalizer;
use ApiCore\Serializer\NormalizerInterface;
use ApiCore\Serializer\Serializer;
use ApiCore\Serializer\SerializerInterface;
use ReflectionClass;

class NormalizerHandler implements HandlerInterface
{
    public function supports(?object $instance, ReflectionClass $reflectionClass): bool
    {
        return $reflectionClass->getName() === NormalizerInterface::class;
    }

    public function handle(?object $instance, ReflectionClass $reflectionClass): ?object
    {
        return Container::getInstance()->get(Normalizer::class);
    }
}
