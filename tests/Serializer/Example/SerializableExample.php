<?php

declare(strict_types=1);

namespace ApiCoreTest\Serializer\Example;

use ApiCore\Serializer\Attribute\SerializedName;

class SerializableExample
{
    private string $username;

    private int $age;

    private float $cashFlow;

    private array $cars;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getCashFlow(): float
    {
        return $this->cashFlow;
    }

    public function setCashFlow(float $cashFlow): self
    {
        $this->cashFlow = $cashFlow;

        return $this;
    }

    public function getCars(): array
    {
        return $this->cars;
    }

    public function setCars(array $cars): self
    {
        $this->cars = $cars;

        return $this;
    }
}
