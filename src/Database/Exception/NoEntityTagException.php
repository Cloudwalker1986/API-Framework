<?php

declare(strict_types=1);

namespace ApiCore\Database\Exception;

use ApiCore\Database\Attribute\Entity;

class NoEntityTagException extends \RuntimeException
{
    public function __construct(string $fqcn = '')
    {
        parent::__construct(
            sprintf(
                'Target entity %s has no "%s" attribute assigned',
                $fqcn,
                Entity::class
            )
        );
    }
}
