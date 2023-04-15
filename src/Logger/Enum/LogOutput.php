<?php

declare(strict_types=1);

namespace ApiCore\Logger\Enum;

enum LogOutput: string
{
    case STDERR = 'stdErr';
    case STDOUT = 'stdOut';
    case FILE = 'file';
}
