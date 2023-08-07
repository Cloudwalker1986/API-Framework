<?php

declare(strict_types=1);

namespace ApiCoreTest\Database\Hydrator\Example;

use ApiCore\Database\Attribute\Column;
use ApiCore\Database\Attribute\Entity;
use ApiCore\Database\Attribute\Primary;
use ApiCore\Database\Enum\ColumnType;
use ApiCore\Database\Enum\PrimaryKeyValue;

#[Entity('user')]
class HydratedBySetters
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

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): HydratedBySetters
    {
        $this->firstName =
            $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): HydratedBySetters
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): HydratedBySetters
    {
        $this->age = $age;

        return $this;
    }

    public function getBirthday(): \DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): HydratedBySetters
    {
        $this->birthday = $birthday;

        return $this;
    }
}
