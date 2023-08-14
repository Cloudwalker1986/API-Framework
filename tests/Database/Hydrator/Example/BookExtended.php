<?php

declare(strict_types=1);

namespace ApiCoreTest\Database\Hydrator\Example;

use ApiCore\Database\Attribute\Column;
use ApiCore\Database\Attribute\Entity;
use ApiCore\Database\Attribute\ManyToOne;
use ApiCore\Database\Attribute\Primary;
use ApiCore\Database\Enum\ColumnType;
use ApiCore\Database\Enum\PrimaryKeyValue;

#[Entity('book')]
class BookExtended
{
    #[Primary(PrimaryKeyValue::TYPE_UUID)]
    #[Column(type: ColumnType::VARCHAR, length: 36)]
    private string $id;

    #[Column(type: ColumnType::DECIMAL)]
    private float $price;

    #[ManyToOne('books')]
    private AuthorExtended $author;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): BookExtended
    {
        $this->id = $id;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): BookExtended
    {
        $this->price = $price;

        return $this;
    }

    public function getAuthor(): AuthorExtended
    {
        return $this->author;
    }

    public function setAuthor(AuthorExtended $author): BookExtended
    {
        $this->author = $author;

        return $this;
    }
}
