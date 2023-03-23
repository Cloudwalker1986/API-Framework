<?php

declare(strict_types=1);

namespace ApiCore\Config\Attribute;

#[\Attribute(\Attribute::IS_REPEATABLE|\Attribute::TARGET_PARAMETER|\Attribute::TARGET_METHOD)]
class Value
{
    public function __construct(private readonly string $key)
    {
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
