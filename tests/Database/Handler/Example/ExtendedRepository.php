<?php

declare(strict_types=1);

namespace ApiCoreTest\Database\Handler\Example;

use ApiCore\Database\Attribute\Repository;
use ApiCore\Database\ReadRepositoryInterface;

#[Repository(DbEntity::class)]
interface ExtendedRepository extends SimpleRepository, ReadRepositoryInterface
{

}
