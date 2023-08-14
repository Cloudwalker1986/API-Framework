<?php

declare(strict_types=1);

namespace ApiCore\Database\Attribute;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_PROPERTY)]
class ManyToOne
{
    public function __construct(private readonly string $parentProperty)
    {
    }

    public function getParentProperty(): string
    {
        return $this->parentProperty;
    }
}
