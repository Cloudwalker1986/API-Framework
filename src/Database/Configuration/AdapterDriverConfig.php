<?php

declare(strict_types=1);

namespace ApiCore\Database\Configuration;

use ApiCore\Config\Attribute\Configuration;
use ApiCore\Config\Attribute\Value;
use ApiCore\Database\Enum\Adapter;
use ApiCore\Database\Exception\UnknownDatabaseAdapterException;

#[Configuration]
class AdapterDriverConfig
{
    private ?Adapter $driver;

    public function getDriver(): Adapter
    {
        return $this->driver;
    }

    #[Value('database.driver', 'mysqli')]
    public function setDriver(string $driver): AdapterDriverConfig
    {
        $this->driver = Adapter::tryFrom($driver);

        if ($this->driver === null) {
            throw new UnknownDatabaseAdapterException(
                $driver,
                array_map(fn (Adapter $adapter) => $adapter->value, Adapter::cases()));
        }

        return $this;
    }
}
