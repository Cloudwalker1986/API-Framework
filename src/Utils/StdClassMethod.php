<?php

declare(strict_types=1);

namespace ApiCore\Utils;

use ApiCore\Dependency\Resolver\ClassResolver;

class StdClassMethod
{
    public static function hasConstructor(\ReflectionClass $class): bool
    {
        return $class->hasMethod(ClassResolver::METHOD_CONSTRUCT);
    }

    public static function isSetterMethod(\ReflectionMethod $method): bool
    {
        return str_starts_with($method->getName(), ClassResolver::METHOD_SETTER);
    }
}
