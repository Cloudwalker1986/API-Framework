<?php

declare(strict_types=1);

namespace ApiCore\Database\Enum;
enum Adapter: string
{
    case PDO = 'pdo';
    case MYSQLI = 'mysqli';
}
