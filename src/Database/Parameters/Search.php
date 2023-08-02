<?php

declare(strict_types=1);

namespace ApiCore\Database\Parameters;

use JetBrains\PhpStorm\ArrayShape;

class Search
{
    public const CONSTRAINT_AND = 'and';
    public const CONSTRAINT_OR = 'or';

    public const IS_EQUALS = 'equals';

    public const LIKE = 'like';

    private array $parameters = [];

    public function set(
        string $column,
        string|int|float|array $value,
        string $searchBy = self::IS_EQUALS,
        string $constraint = self::CONSTRAINT_AND,
        ?string $table = null
    ): Search
    {
        $this->parameters[$column] = [
            'value' => $value,
            'operator' => $constraint,
            'searchBy' => $searchBy,
            'table' => $table
        ];

        return $this;
    }

    public function getAll(): array
    {
        return $this->parameters;
    }
}
