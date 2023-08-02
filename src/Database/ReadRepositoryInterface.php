<?php

declare(strict_types=1);

namespace ApiCore\Database;

use ApiCore\Database\Parameters\OrderDirection;
use ApiCore\Database\Parameters\Search;
use ApiCore\Utils\Collection;

interface ReadRepositoryInterface
{
    public function findByCriteria(Search $search, ?OrderDirection $direction): Collection;

    public function findOneByCriteriaOrNull(Search $search): ?object;
}
