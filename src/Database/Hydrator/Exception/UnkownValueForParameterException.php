<?php

declare(strict_types=1);

namespace ApiCore\Database\Hydrator\Exception;

use InvalidArgumentException;

class UnkownValueForParameterException extends InvalidArgumentException
{
    private string $msg = 'For parameter "%s" there is no mapped value provided';

    public function __construct(string $property = "")
    {
        parent::__construct(sprintf($this->msg, $property));
    }
}
