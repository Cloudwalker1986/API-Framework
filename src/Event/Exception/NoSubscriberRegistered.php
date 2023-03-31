<?php

declare(strict_types=1);

namespace ApiCore\Event\Exception;

use Throwable;

class NoSubscriberRegistered extends \RuntimeException
{
    private string $errorMsg = 'Event of "%s" has no subscriber assigned.';

    public function __construct(
        string $eventName
    ) {
        parent::__construct(sprintf($this->message, $eventName));
    }
}
