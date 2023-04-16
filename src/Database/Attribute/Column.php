<?php

declare(strict_types=1);

namespace ApiCore\Database\Attribute;

use ApiCore\Database\Enum\ColumnType;

#[\Attribute(\Attribute::IS_REPEATABLE|\Attribute::TARGET_PROPERTY)]
class Column
{
    private array $defaultOption = [
        'decimal' => [
            'precision' => [5,2]
        ],
        'default' => ''
    ];

    public function __construct(
        private readonly string $name = '',
        private readonly ColumnType $type = ColumnType::VARCHAR,
        private readonly int $length = 10,
        private array $options = [],
        private readonly string $comment = '',
        private readonly bool $nullable = true
    ) {
        $this->options = [
            ...$this->defaultOption,
            ...$this->options
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): ColumnType
    {
        return $this->type;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
