<?php

declare(strict_types=1);

namespace ApiCore\Database\Attribute;

#[\Attribute(\Attribute::IS_REPEATABLE|\Attribute::TARGET_CLASS)]
class UniqueConstraint
{
    public function __construct(
        private readonly array $columns,
        private readonly string $name = ''
    ) {
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
