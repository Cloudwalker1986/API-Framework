<?php

declare(strict_types=1);

namespace ApiCore\Database\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Entity
{
    public function __construct(
        private readonly string $tableName = ''
    ) {
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }
}
