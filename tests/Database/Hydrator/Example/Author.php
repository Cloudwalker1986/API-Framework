<?php

declare(strict_types=1);

namespace ApiCoreTest\Database\Hydrator\Example;

use ApiCore\Database\Attribute\Column;
use ApiCore\Database\Attribute\Entity;
use ApiCore\Database\Attribute\OneToMany;
use ApiCore\Database\Attribute\Primary;
use ApiCore\Database\Enum\ColumnType;
use ApiCore\Database\Enum\LazyLoadingCollectionStrategy;
use ApiCore\Database\Enum\PrimaryKeyValue;
use ApiCore\Utils\CollectionInterface;

#[Entity('author')]
class Author
{
    #[Column(type: ColumnType::INT)]
    #[Primary(PrimaryKeyValue::TYPE_INTEGER)]
    private int $id;

    #[Column(type: ColumnType::VARCHAR)]
    private string $name;

    #[OneToMany(LazyLoadingCollectionStrategy::ON_DEMAND_EACH_TIME, Book::class, 'author')]
    private CollectionInterface $books;

    /**
     * @param int $id
     * @param string $name
     * @param CollectionInterface $books
     */
    public function __construct(int $id, string $name, CollectionInterface $books)
    {
        $this->id = $id;
        $this->name = $name;
        $this->books = $books;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBooks(): CollectionInterface
    {
        return $this->books;
    }
}
