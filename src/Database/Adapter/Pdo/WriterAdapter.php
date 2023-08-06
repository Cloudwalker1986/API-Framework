<?php

declare(strict_types=1);

namespace ApiCore\Database\Adapter\Pdo;

use ApiCore\Database\Adapter\WriterAdapterInterface;
use ApiCore\Database\Configuration\ConnectionConfig;
use ApiCore\Database\Result\Statement;
use ApiCore\Dependency\Hook\AfterConstruct\Attribute\AfterConstruct;
use PDO;

class WriterAdapter extends BaseAdapter implements WriterAdapterInterface
{
    public function persists(string $query, array $bindParams): Statement
    {
        // TODO: Implement persists() method.
    }

    public function delete(string $query, array $bindParams): Statement
    {
        // TODO: Implement delete() method.
    }
}
