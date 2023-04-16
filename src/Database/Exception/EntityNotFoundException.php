<?php

declare(strict_types=1);

namespace ApiCore\Database\Exception;

use InvalidArgumentException;

class EntityNotFoundException extends InvalidArgumentException
{
    public function __construct(string $fqcn = '')
    {
        parent::__construct(
            sprintf(
                'Unable to locate the following class %s',
                $fqcn
            )
        );
    }
}
