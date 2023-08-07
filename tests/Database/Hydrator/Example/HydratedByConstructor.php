<?php

declare(strict_types=1);

namespace ApiCoreTest\Database\Hydrator\Example;

use ApiCore\Database\Attribute\Column;
use ApiCore\Database\Attribute\Entity;
use ApiCore\Database\Attribute\Primary;
use ApiCore\Database\Enum\ColumnType;
use ApiCore\Database\Enum\PrimaryKeyValue;

#[Entity('user')]
class HydratedByConstructor
{
    #[Column('first_name')]
    #[Primary(keyValue: PrimaryKeyValue::TYPE_UUID)]
    private string $firstName;

    #[Column('surname')]
    private string $lastName;

    #[Column(type: ColumnType::INT)]
    private int $age;

    #[Column(type: ColumnType::DATETIME)]
    private \DateTimeInterface $birthday;

    public function __construct(string $firstName, string $lastName, int $age, \DateTimeInterface $birthday)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->age = $age;
        $this->birthday = $birthday;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getBirthday(): \DateTimeInterface
    {
        return $this->birthday;
    }
}
