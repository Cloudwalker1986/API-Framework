<?php

declare(strict_types=1);

namespace ApiCore\Database\Adapter\Mysqli;

use ApiCore\Database\Configuration\ConnectionConfig;
use ApiCore\Dependency\Hook\AfterConstruct\Attribute\AfterConstruct;
use mysqli;

class BaseAdapter
{

    private mysqli $connection;

    public function __construct(private readonly ConnectionConfig $config)
    {
    }

    #[AfterConstruct]
    public function init(): void
    {
        $this->connection = new mysqli(
            $this->config->getUrl(),
            $this->config->getUser(),
            $this->config->getPassword(),
            $this->config->getName()
        );
    }

    protected function getConnection(): mysqli
    {
        return $this->connection;
    }
}
