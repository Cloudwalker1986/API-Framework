<?php

declare(strict_types=1);

namespace ApiCore\Logger\Writer;

use ApiCore\Logger\Enum\LogFormat;
use ApiCore\Logger\Enum\LogLevel;

class BaseWriter
{
    public function __construct(private readonly LogFormat $logFormat)
    {
    }

    protected function release(LogLevel $logLevel, string $message, array $context, $resource): void
    {
        match ($this->logFormat) {
            LogFormat::JSON => $this->logJsonFormat($logLevel, $message, $context, $resource),
            LogFormat::TExT => $this->logTextFormat($logLevel, $message, $context, $resource)
        };
    }
    
    protected function logJsonFormat(LogLevel $logLevel, string $message, array $context, $resource): void
    {
        $data = [
            'logLevel' => $logLevel->value,
            'message' => $message,
            'context' => $context
        ];

        fwrite($resource, json_encode($data));
    }

    private function logTextFormat(LogLevel $logLevel, string $message, array $context, $resource): void
    {
        $data = sprintf('[%s] - %s', $logLevel->value, $message);

        fwrite($resource, $data);
    }
}
