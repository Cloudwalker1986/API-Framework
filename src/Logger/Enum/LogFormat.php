<?php

declare(strict_types=1);

namespace ApiCore\Logger\Enum;

enum LogFormat: string
{
    case JSON = 'json';
    case TExT = 'text';
}
