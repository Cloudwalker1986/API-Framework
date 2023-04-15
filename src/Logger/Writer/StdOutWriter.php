<?php

declare(strict_types=1);

namespace ApiCore\Logger\Writer;

use ApiCore\Logger\Enum\LogFormat;
use ApiCore\Logger\Enum\LogLevel;

class StdOutWriter extends BaseWriter implements WriterInterface
{
    public function write(LogLevel $logLevel, string $message, array $context): void
    {
        $resource = fopen('php://stdout', "w");

        if ($resource === null) {
            return;
        }

        $this->release($logLevel, $message, $context, $resource);

        fclose($resource);
    }
}
