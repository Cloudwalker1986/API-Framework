<?php

declare(strict_types=1);

namespace ApiCore\Database\Handler;

use ApiCore\Database\Adapter\ReaderAdapterInterface;
use ApiCore\Database\Adapter\WriterAdapterInterface;
use ApiCore\Database\Configuration\AdapterDriverConfig;
use ApiCore\Database\Factory\AdapterFactory;
use ApiCore\Dependency\Container;
use ApiCore\Dependency\Handler\HandlerInterface;
use ReflectionClass;

class AdapterInterfaceHandler implements HandlerInterface
{
    public function __construct(
        private readonly Container $container,
        private readonly AdapterFactory $factory
    ) {
    }

    public function supports(?object $instance, ReflectionClass $reflectionClass): bool
    {
        return in_array(
            $reflectionClass->getName(),
            [ReaderAdapterInterface::class, WriterAdapterInterface::class],
            true
        );
    }

    public function handle(?object $instance, ReflectionClass $reflectionClass): ?object
    {
        /** @var AdapterDriverConfig $driverConfig */
        $driverConfig = $this->container->get(AdapterDriverConfig::class);

        return $this->factory->getAdapter($driverConfig, $reflectionClass);
    }
}
