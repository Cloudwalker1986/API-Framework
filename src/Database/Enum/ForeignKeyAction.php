<?php

declare(strict_types=1);

namespace ApiCore\Database\Enum;

enum ForeignKeyAction: string
{
    case NO_ACTION = 'NO ACTION';
    case CASCADE = 'CASCADE';
    case SET_NULL = 'SET NULL';
    case RESTRICT = 'RESTRICT';
    case SET_DEFAULT = 'SET DEFAULT';
}
