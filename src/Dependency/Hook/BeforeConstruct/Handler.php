<?php

declare(strict_types=1);

namespace ApiCore\Dependency\Hook\BeforeConstruct;

use ApiCore\Dependency\Handler\HandlerInterface;
use ApiCore\Dependency\Hook\BeforeConstruct\Attribute\BeforeConstruct;
use ReflectionClass;

class Handler implements HandlerInterface
{
    public function supports(?object $instance, ReflectionClass $reflectionClass): bool
    {
        foreach($reflectionClass->getMethods() as $method) {
            if (!empty($method->getAttributes(BeforeConstruct::class))) {
                return true;
            }
        }
        return false;
    }

    public function handle(?object $instance, ReflectionClass $reflectionClass): ?object
    {
        foreach($reflectionClass->getMethods() as $method) {
            if (!empty($method->getAttributes(BeforeConstruct::class))) {
                if ($method->isPublic() && $method->isStatic()) {
                    return call_user_func([$reflectionClass->getName(), $method->getName()]);
                }
            }
        }
        return null;
    }
}
