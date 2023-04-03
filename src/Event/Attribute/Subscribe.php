<?php

declare(strict_types=1);

namespace ApiCore\Event\Attribute;

use Attribute;

#[\Attribute(Attribute::IS_REPEATABLE|\Attribute::TARGET_METHOD)]
class Subscribe
{
    public function __construct(private readonly string $eventName)
    {
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }
}
