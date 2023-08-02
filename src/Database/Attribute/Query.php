<?php

declare(strict_types=1);

namespace ApiCore\Database\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Query
{
    public function __construct(private readonly string $query) {}

    public function getQuery(): string
    {
        return $this->query;
    }
}
