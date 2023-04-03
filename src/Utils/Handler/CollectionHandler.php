<?php

declare(strict_types=1);

namespace ApiCore\Utils\Handler;

use ApiCore\Dependency\Handler\HandlerInterface;
use ApiCore\Utils\Collection;
use ApiCore\Utils\CollectionInterface;
use ApiCore\Utils\HashMap;
use ApiCore\Utils\Map;
use ReflectionClass;

class CollectionHandler implements HandlerInterface
{
    public function supports(?object $instance, ReflectionClass $reflectionClass): bool
    {
        return $reflectionClass->getName() === CollectionInterface::class;
    }

    public function handle(?object $instance, ReflectionClass $reflectionClass): ?object
    {
        return new Collection();
    }
}
