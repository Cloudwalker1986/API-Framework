<?php

declare(strict_types=1);

namespace ApiCore\Logger\Enum;

enum LogLevel: string
{
    case ERROR = 'error';
    case CRITICAL = 'critical';
    case WARNING = 'warning';
    case INFO = 'info';
    case DEBUG = 'debug';
}
