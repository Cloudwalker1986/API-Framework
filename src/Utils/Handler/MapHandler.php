<?php

declare(strict_types=1);

namespace ApiCore\Utils\Handler;

use ApiCore\Dependency\Handler\HandlerInterface;
use ApiCore\Utils\HashMap;
use ApiCore\Utils\Map;
use ReflectionClass;

class MapHandler implements HandlerInterface
{
    public function supports(?object $instance, ReflectionClass $reflectionClass): bool
    {
        return $reflectionClass->getName() === Map::class;
    }

    public function handle(?object $instance, ReflectionClass $reflectionClass): ?object
    {
        return new HashMap();
    }
}
