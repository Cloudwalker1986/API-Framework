<?php

declare(strict_types=1);

namespace ApiCore\Database;

interface CrudRepositoryInterface
{
    public function persists(object $entity): void;

    public function delete(object $entity): void;

}
