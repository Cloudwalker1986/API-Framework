<?php

declare(strict_types=1);

namespace ApiCore\Database;

use ApiCore\Database\Adapter\ReaderAdapterInterface;
use ApiCore\Utils\Collection;

abstract class BaseRepository
{
    public function __construct(private readonly ReaderAdapterInterface $readerAdapter)
    {
    }

    protected function handleQuerySingleEntity(string $query, array $parameters): ?object
    {

    }

    protected function handleQueryMultipleEntities(string $query, array $parameters): Collection
    {

    }
}
