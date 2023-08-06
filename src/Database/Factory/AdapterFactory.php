<?php

declare(strict_types=1);

namespace ApiCore\Database\Factory;

use ApiCore\Database\Adapter\Pdo;
use ApiCore\Database\Adapter\Mysqli;
use ApiCore\Database\Adapter\WriterAdapterInterface;
use ApiCore\Database\Configuration\AdapterDriverConfig;
use ApiCore\Database\Configuration\ConnectionConfig;
use ApiCore\Database\Enum\Adapter;
use ApiCore\Dependency\Container;

class AdapterFactory
{
    public function __construct(private readonly Container $container)
    {
    }

    public function getAdapter(AdapterDriverConfig $driver, \ReflectionClass $adapter): object
    {
        /** @var ConnectionConfig $config */
        $config = $this->container->get(ConnectionConfig::class);

        if ($adapter->getName() === WriterAdapterInterface::class) {
            return match($driver->getDriver()) {
                Adapter::PDO => new Pdo\WriterAdapter($config),
                default => new Mysqli\WriterAdapter($config)
            };
        }
        return match($driver) {
            Adapter::PDO => new Pdo\ReaderAdapter($config),
            default => new mysqli\ReaderAdapter($config)
        };
    }
}
