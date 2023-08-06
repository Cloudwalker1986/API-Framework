<?php

declare(strict_types=1);

namespace ApiCore\Database\Adapter\Pdo;

use ApiCore\Database\Adapter\ReaderAdapterInterface;
use ApiCore\Database\Configuration\ConnectionConfig;
use ApiCore\Dependency\Hook\AfterConstruct\Attribute\AfterConstruct;
use PDO;

class ReaderAdapter extends BaseAdapter implements ReaderAdapterInterface
{
    public function fetchRow(string $query, array $bindingParameters): array
    {
        // TODO: Implement fetchRow() method.
    }

    public function fetchAll(string $query, array $bindingParameters): array
    {
        // TODO: Implement fetchAll() method.
    }
}
