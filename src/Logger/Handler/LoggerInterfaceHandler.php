<?php

declare(strict_types=1);

namespace ApiCore\Logger\Handler;

use ApiCore\Dependency\Container;
use ApiCore\Dependency\Handler\HandlerInterface;
use ApiCore\Logger\Logger;
use ApiCore\Logger\LoggerInterface;
use ReflectionClass;

class LoggerInterfaceHandler implements HandlerInterface
{
    public function __construct(
        private readonly Container $container
    ) {
    }

    public function supports(?object $instance, ReflectionClass $reflectionClass): bool
    {
        return $reflectionClass->getName() === LoggerInterface::class;
    }

    public function handle(?object $instance, ReflectionClass $reflectionClass): ?object
    {
        return $this->container->get(Logger::class);
    }
}
