<?php

declare(strict_types=1);

namespace ApiCore\Config\Attribute;

#[\Attribute(
    \Attribute::IS_REPEATABLE|\Attribute::TARGET_PARAMETER|\Attribute::TARGET_METHOD|\Attribute::TARGET_PROPERTY
)]
class Value
{
    public function __construct(private readonly string $key, private readonly mixed $default = null)
    {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getDefault(): mixed
    {
        return $this->default;
    }
}
