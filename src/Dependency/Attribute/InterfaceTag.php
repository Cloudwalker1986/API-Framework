<?php

declare(strict_types=1);

namespace ApiCore\Dependency\Attribute;

#[\Attribute(\Attribute::IS_REPEATABLE|\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class InterfaceTag
{
    public function __construct(private readonly string $classToLoad)
    {
    }

    public function getClassToLoad(): string
    {
        return $this->classToLoad;
    }
}
