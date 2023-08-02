<?php

declare(strict_types=1);

namespace ApiCore\Database;

use ApiCore\Utils\Collection;

abstract class BaseRepository
{
    protected function handleQuerySingleEntity(string $query, array $parameters): ?object
    {

    }

    protected function handleQueryMultipleEntities(string $query, array $parameters): Collection
    {

    }
}
