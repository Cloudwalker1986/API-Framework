<?php

declare(strict_types=1);

namespace ApiCore\Database;

use ApiCore\Database\Parameters\OrderDirection;
use ApiCore\Database\Parameters\Search;
use ApiCore\Utils\Collection;

abstract class ReaderRepository extends BaseRepository implements ReadRepositoryInterface
{
    protected function searchForOne(Search $search): ?object
    {

    }

    protected function searchForCollection(Search $search, ?OrderDirection $orderDirection = null): Collection
    {

    }
}
