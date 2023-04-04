<?php

declare(strict_types=1);

namespace ApiCore\Logger;

interface LoggerInterface
{
    public function error(string $message, array $context = []): void;

    public function critical(string $message, array $context = []): void;

    public function warning(string $message, array $context = []): void;

    public function info(string $message, array $context = []): void;

    public function debug(string $message, array $context = []): void;
}
