<?php

declare(strict_types=1);

namespace ApiCore\Dependency\Handler;

use ReflectionClass;

interface HandlerInterface
{
    public function supports(?object $instance, ReflectionClass $reflectionClass): bool;

    public function handle(?object $instance, ReflectionClass $reflectionClass): ?object;
}
