<?php

declare(strict_types=1);

namespace ApiCore\Database;

use ApiCore\Database\Result\Statement;

interface CrudRepositoryInterface
{
    public function persists(object $entity): Statement;

    public function delete(object $entity): Statement;

}
