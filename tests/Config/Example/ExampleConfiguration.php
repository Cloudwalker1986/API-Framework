<?php

declare(strict_types=1);

namespace ApiCoreTest\Config\Example;

use ApiCore\Config\Attribute\Configuration;
use ApiCore\Config\Attribute\Value;

#[Configuration]
class ExampleConfiguration
{
    private string $value;

    private array $items;

    private int $intVal;

    public function setConfigValue(#[Value('firstLevel.secondLevel')] $val): void
    {
        $this->value = $val;
    }

    #[Value('firstLevel.secondThird.thirdLevel.fourth')]
    public function randomNameForItems(array $items): void
    {
        $this->items = $items;
    }

    #[Value('firstLevel.secondSecond')]
    public function random(int $intVal): void
    {
        $this->intVal = $intVal;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getIntVal(): int
    {
        return $this->intVal;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
