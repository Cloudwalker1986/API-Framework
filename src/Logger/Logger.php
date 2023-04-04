<?php

declare(strict_types=1);

namespace ApiCore\Logger;

use ApiCore\Logger\Enum\LogType;

class Logger implements LoggerInterface
{
    public function error(string $message, array $context = []): void
    {
        $this->log(LogType::ERROR, $message, $context);
    }

    public function critical(string $message, array $context = []): void
    {
        $this->log(LogType::CRITICAL, $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->log(LogType::WARNING, $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log(LogType::INFO, $message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->log(LogType::DEBUG, $message, $context);
    }

    private function log(LogType $type, string $message, array $context): void
    {
        
    }
}
