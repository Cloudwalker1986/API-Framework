<?php

declare(strict_types=1);

namespace ApiCore\Database\Adapter\Pdo;

use ApiCore\Database\Configuration\ConnectionConfig;
use ApiCore\Dependency\Hook\AfterConstruct\Attribute\AfterConstruct;
use PDO;

class BaseAdapter
{

    private PDO $connection;

    public function __construct(private readonly ConnectionConfig $config)
    {
    }

    #[AfterConstruct]
    public function initConnection(): void
    {
        $this->connection = new PDO(
            sprintf('mysql:dbname=%s;host=%s', $this->config->getName(), $this->config->getUrl()),
            $this->config->getUser(),
            $this->config->getPassword()
        );
    }

    protected function getConnection(): PDO
    {
        return $this->connection;
    }
}
