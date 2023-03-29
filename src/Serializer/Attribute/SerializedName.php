<?php

declare(strict_types=1);

namespace ApiCore\Serializer\Attribute;

#[\Attribute(\Attribute::IS_REPEATABLE|\Attribute::TARGET_PROPERTY|\Attribute::TARGET_PARAMETER)]
class SerializedName
{
    public function __construct(private readonly string $serializedName)
    {
    }

    public function getSerializedName(): string
    {
        return $this->serializedName;
    }
}
