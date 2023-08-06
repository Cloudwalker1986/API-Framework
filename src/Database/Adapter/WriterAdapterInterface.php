<?php

declare(strict_types=1);

namespace ApiCore\Database\Adapter;

use ApiCore\Database\Result\Statement;

interface WriterAdapterInterface
{
    public function persists(string $query, array $bindParams): Statement;

    public function delete(string $query, array $bindParams): Statement;
}
