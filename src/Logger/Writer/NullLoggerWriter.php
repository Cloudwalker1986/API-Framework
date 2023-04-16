<?php

declare(strict_types=1);

namespace ApiCore\Logger\Writer;

use ApiCore\Logger\Enum\LogLevel;

class NullLoggerWriter extends BaseWriter implements WriterInterface
{
    public function write(LogLevel $logLevel, string $message, array $context): void
    {
        // TODO: Implement write() method.
    }
}
