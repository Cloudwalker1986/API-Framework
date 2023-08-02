<?php

declare(strict_types=1);

namespace ApiCoreTest\Database\Handler\Example;

use ApiCore\Database\Attribute\Repository;
use ApiCore\Database\CrudRepositoryInterface;

#[Repository(DbEntity::class)]
interface SimpleCrudRepository extends CrudRepositoryInterface
{

}
