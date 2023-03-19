<?php

declare(strict_types=1);

namespace ApiCore\Dependency\Hook\AfterConstruct;

use ApiCore\Dependency\Handler\HandlerInterface;
use ApiCore\Dependency\Hook\AfterConstruct\Attribute\AfterConstruct;
use ReflectionClass;

class Handler implements HandlerInterface
{
    public function supports(?object $instance, ReflectionClass $reflectionClass): bool
    {
        if ($instance === null) {
            return false;
        }

        foreach($reflectionClass->getMethods() as $method) {
            if (!empty($method->getAttributes(AfterConstruct::class))) {
                return true;
            }
        }
        return false;
    }

    public function handle(?object $instance, ReflectionClass $reflectionClass): ?object
    {
        if ($instance === null) {
            return null;
        }

        foreach($reflectionClass->getMethods() as $method) {
            if (!empty($method->getAttributes(AfterConstruct::class)) && $method->isPublic()) {
                $instance->{$method->getName()}();
            }
        }

        return $instance;
    }
}
