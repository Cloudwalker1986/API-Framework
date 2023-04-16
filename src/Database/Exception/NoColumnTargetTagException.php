<?php

declare(strict_types=1);

namespace ApiCore\Database\Exception;

class NoColumnTargetTagException extends \InvalidArgumentException
{
    public function __construct(string $entityFqcn, string $column)
    {
        parent::__construct(
            sprintf(
                'Unable to locate relation for %s of expected column %f',
                $entityFqcn,
                $column
            )
        );
    }
}
