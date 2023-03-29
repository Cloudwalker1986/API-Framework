<?php

declare(strict_types=1);

namespace ApiCoreTest\Serializer\Example;

use ApiCore\Serializer\Attribute\SerializedName;

class SerializedNameWithConstructorExample
{
    public function __construct(
        #[SerializedName('nameOfUser')] private readonly string $username,
        #[SerializedName('ageInYears')] private readonly int $age,
        #[SerializedName('howManyMoney')] private readonly float $cashFlow,
        #[SerializedName('carItems')] private readonly  array $cars
    ) {
    }

    public function getUsername(): string
    {
        return $this->username;
    }
    public function getAge(): int
    {
        return $this->age;
    }

    public function getCashFlow(): float
    {
        return $this->cashFlow;
    }

    public function getCars(): array
    {
        return $this->cars;
    }
}
