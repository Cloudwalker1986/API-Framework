<?php
declare(strict_types=1);

namespace ApiCoreTest\Event\Example;

class Payload
{
    private int $i = 2;

    public function increase(): void
    {
        $this->i++;
    }

    public function getI(): int
    {
        return $this->i;
    }
}
