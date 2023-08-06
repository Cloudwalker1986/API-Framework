<?php

declare(strict_types=1);

namespace ApiCore\Database\Adapter;

interface ReaderAdapterInterface
{
    public function fetchRow(string $query, array $bindingParameters);

    public function fetchAll(string $query, array $bindingParameters);
}
