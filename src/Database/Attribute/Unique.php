<?php

declare(strict_types=1);

namespace ApiCore\Database\Attribute;

#[\Attribute(\Attribute::IS_REPEATABLE|\Attribute::TARGET_PROPERTY)]
class Unique
{
    public function __construct(
        private readonly string $name = ''
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
