<?php
declare(strict_types=1);

namespace ApiCore\Logger\Handler;

use ApiCore\Dependency\Container;
use ApiCore\Dependency\Handler\HandlerInterface;
use ApiCore\Logger\Writer\WriterInterface;
use ReflectionClass;
use ApiCore\Logger\Factory\LoggerFactory;

class LogWriterInterfaceHandler implements HandlerInterface
{
    public function __construct(private readonly Container $container)
    {
    }

    public function supports(?object $instance, ReflectionClass $reflectionClass): bool
    {
        return $reflectionClass->getName() === WriterInterface::class;
    }

    public function handle(?object $instance, ReflectionClass $reflectionClass): ?object
    {
        /** @var LoggerFactory $factory */
        $factory = $this->container->get(LoggerFactory::class);

        return $factory->getWriter();
    }
}
