<?php

declare(strict_types=1);

namespace ApiCore\Database\Exception;

use RuntimeException;

class UnknownDatabaseAdapterException extends RuntimeException
{
    private string $msg = 'Your selected driver "%s" is not supported. The following driver are supported "%s"';

    public function __construct(string $driver, array $supported)
    {
        parent::__construct(
            sprintf(
                $this->msg,
                $driver,
                implode(', ', $supported)
            )
        );
    }
}
