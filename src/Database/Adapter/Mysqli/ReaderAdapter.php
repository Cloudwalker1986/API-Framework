<?php
declare(strict_types=1);


namespace ApiCore\Database\Adapter\Mysqli;

use ApiCore\Database\Adapter\ReaderAdapterInterface;

class ReaderAdapter extends BaseAdapter implements ReaderAdapterInterface
{
    public function fetchRow(string $query, array $bindingParameters)
    {
        // TODO: Implement fetchRow() method.
    }

    public function fetchAll(string $query, array $bindingParameters)
    {
        // TODO: Implement fetchAll() method.
    }
}
