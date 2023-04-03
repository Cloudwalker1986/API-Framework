<?php

declare(strict_types=1);

namespace ApiCoreTest\Event\Example;

use ApiCore\Event\Attribute\Subscribe;

class EventSubscriber
{
    public const EVENT_NAME = 'hello.world';

    #[Subscribe(self::EVENT_NAME)]
    public function methodEventHandler(object $payload): void
    {
        if ($payload instanceof Payload) {
            $payload->increase();
        }
    }
}
