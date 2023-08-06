<?php

declare(strict_types=1);

namespace ApiCore\Database;

use ApiCore\Database\Adapter\WriterAdapterInterface;
use ApiCore\Database\Result\Statement;

abstract class CrudRepository
{
    public function __construct(private readonly WriterAdapterInterface $writerAdapter)
    {
    }

    protected function createOrUpdate(object $entity): Statement
    {

    }

    protected function remove(object $entity): Statement
    {

    }
}
