<?php

declare(strict_types=1);

namespace ApiCore\Database\Enum;

enum PrimaryKeyValue: string
{
    case TYPE_INTEGER = 'integer';
    case TYPE_UUID = 'uuid';
}
