<?php

declare(strict_types=1);

namespace ApiCore\Logger;

use ApiCore\Logger\Config\LoggerConfig;
use ApiCore\Logger\Enum\LogLevel;
use ApiCore\Logger\Writer\WriterInterface;

class Logger implements LoggerInterface
{
    public function __construct(private readonly WriterInterface $writer)
    {
    }

    public function error(string $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function critical(string $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    private function log(LogLevel $logLevel, string $message, array $context): void
    {
        $this->writer->write($logLevel, $message, $context);
    }
}
