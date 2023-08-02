<?php

declare(strict_types=1);

namespace ApiCoreTest\Database\Handler\Example;

use ApiCore\Database\Attribute\Query;
use ApiCore\Database\Attribute\Repository;
use ApiCore\Utils\Collection;

#[Repository(DbEntity::class)]
interface SimpleRepository
{
    #[Query('WHERE id = :id')]
    public function findById(int $id): ?DbEntity;

    #[Query('LIMIT :limit OFFSET :offset')]
    public function findAll(int $limit, int $offset): Collection;

    #[Query('WHERE name LIKE ":search%" LIMIT :limit OFFSET :offset')]
    public function findAllBySearch(string $search, int $limit, int $offset): Collection;
}
