<?php

declare(strict_types=1);

namespace ApiCoreTest\Database\Hydrator\Example;

use ApiCore\Database\Attribute\Column;
use ApiCore\Database\Attribute\Entity;
use ApiCore\Database\Attribute\Primary;
use ApiCore\Database\Enum\ColumnType;
use ApiCore\Database\Enum\PrimaryKeyValue;

#[Entity('book')]
class Book
{
    #[Primary(PrimaryKeyValue::TYPE_UUID)]
    #[Column(type: ColumnType::VARCHAR, length: 36)]
    private string $id;

    #[Column(type: ColumnType::DECIMAL)]
    private float $price;

    private Author $author;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Book
    {
        $this->id = $id;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): Book
    {
        $this->price = $price;

        return $this;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function setAuthor(Author $author): Book
    {
        $this->author = $author;

        return $this;
    }
}
