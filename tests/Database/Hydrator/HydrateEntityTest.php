<?php

declare(strict_types=1);

namespace ApiCoreTest\Database\Hydrator;

use ApiCore\Database\Adapter\ReaderAdapterInterface;
use ApiCore\Database\BaseRepository;
use ApiCore\Database\Exception\ClassNoTaggedAsEntityException;
use ApiCore\Database\Hydrator\Exception\UnkownValueForParameterException;
use ApiCore\Database\Hydrator\HydrateEntity;
use ApiCore\Logger\Enum\LogFormat;
use ApiCore\Logger\Logger;
use ApiCore\Logger\Writer\NullLoggerWriter;
use ApiCoreTest\Database\Hydrator\Example\Author;
use ApiCoreTest\Database\Hydrator\Example\AuthorExtended;
use ApiCoreTest\Database\Hydrator\Example\HydratedByConstructor;
use ApiCoreTest\Database\Hydrator\Example\HydratedBySetters;
use ApiCoreTest\Database\Hydrator\Example\NoEntityTaggedClass;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class HydrateEntityTest extends TestCase
{
    private ?HydrateEntity $hydrator = null;
    public function setUp(): void
    {
        if ($this->hydrator === null) {
            $this->hydrator = new HydrateEntity(new Logger(new NullLoggerWriter(LogFormat::JSON)));
        }
        parent::setUp();
    }

    #[Test]
    public function hydrateWithCollectionOnDemandEachTime(): void
    {
        /** @var MockObject|ReaderAdapterInterface $readerAdapter */
        $readerAdapter = $this->getMockBuilder(ReaderAdapterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $readerAdapter->expects($this->exactly(2))->method('fetchAll')->willReturn(
            [
                [
                    'id' => 'fff53d57-3abb-4ce2-9cc2-99c64b9803e7',
                    'name' => 'This is the best book ever!',
                    'price' => 39.95
                ],
                [
                    'id' => 'e50ad2f1-bea6-4342-b7a5-628cfa2b9e58',
                    'name' => 'A new book',
                    'price' => 459.95
                ]
            ]
        );

        $repository = $this->getMockBuilder(BaseRepository::class)->disableOriginalConstructor()->getMock();
        $repository->method('getAdapter')->willReturn($readerAdapter);

        /** @var Author $author */
        $author = $this->hydrator->hydrate(
            Author::class,
            [
                'id' => 1,
                'name' => 'Goodwin',
            ],
            $repository
        );
        $items = [];

        foreach ($author->getBooks() as $book) {
            $items[] = $book;
        }

        $this->assertCount(2, $items);

        $items = [];
        foreach ($author->getBooks() as $book) {
            $items[] = $book;
        }
        $this->assertCount(2, $items);
    }
    #[Test]
    public function hydrateWithCollectionOneTimeInMemory(): void
    {
        /** @var MockObject|ReaderAdapterInterface $readerAdapter */
        $readerAdapter = $this->getMockBuilder(ReaderAdapterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $readerAdapter->expects($this->once())->method('fetchAll')->willReturn(
            [
                [
                    'id' => 'fff53d57-3abb-4ce2-9cc2-99c64b9803e7',
                    'name' => 'This is the best book ever!',
                    'price' => 39.95
                ],
                [
                    'id' => 'e50ad2f1-bea6-4342-b7a5-628cfa2b9e58',
                    'name' => 'A new book',
                    'price' => 459.95
                ]
            ]
        );

        $repository = $this->getMockBuilder(BaseRepository::class)->disableOriginalConstructor()->getMock();
        $repository->method('getAdapter')->willReturn($readerAdapter);

        /** @var Author $author */
        $author = $this->hydrator->hydrate(
            AuthorExtended::class,
            [
                'id' => 1,
                'name' => 'Goodwin',
            ],
            $repository
        );
        $items = [];

        foreach ($author->getBooks() as $book) {
            $items[] = $book;
        }

        $this->assertCount(2, $items);

        $items = [];
        foreach ($author->getBooks() as $book) {
            $items[] = $book;
        }
        $this->assertCount(2, $items);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function hydrateValidViaConstructor(): void
    {
        $expected = new HydratedByConstructor(
            'Max',
            'Mustermann',
            37,
            new DateTimeImmutable('1986-07-26 02:00:00')
        );

        $this->assertEquals($expected, $this->hydrator->hydrate(
            HydratedByConstructor::class,
            [
                'first_name' => 'Max',
                'surname' => 'Mustermann',
                'age' => '37',
                'birthday' => '1986-07-26 02:00:00'
            ],
            $this->getMockBuilder(BaseRepository::class)->disableOriginalConstructor()->getMock()
        ));
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function hydrateValidViaSetters(): void
    {
        $expected = new HydratedBySetters();
        $expected
            ->setAge(37)
            ->setBirthday(new DateTimeImmutable('1986-07-26 02:00:00'))
            ->setLastName('Mustermann')
            ->setFirstName('Max');

        $this->assertEquals($expected, $this->hydrator->hydrate(
            HydratedBySetters::class,
            [
                'first_name' => 'Max',
                'surname' => 'Mustermann',
                'age' => 37,
                'birthday' => '1986-07-26 02:00:00'
            ],
            $this->getMockBuilder(BaseRepository::class)->disableOriginalConstructor()->getMock()
        ));
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function hydrateInvalidByMissingParameterValue(): void
    {
        $this->expectException(UnkownValueForParameterException::class);
        $this->expectExceptionMessage('For parameter "birthday" there is no mapped value provided');
        $this->hydrator->hydrate(
            HydratedByConstructor::class,
            [
                'first_name' => 'Max',
                'surname' => 'Mustermann',
                'age' => '37',
            ],
            $this->getMockBuilder(BaseRepository::class)->disableOriginalConstructor()->getMock()
        );
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function hydrateInvalid(): void
    {
        $this->expectException(ClassNoTaggedAsEntityException::class);
        $this->expectExceptionMessage(NoEntityTaggedClass::class . ' is not an entity and can´t be hydrated.');

        $this->hydrator->hydrate(
            NoEntityTaggedClass::class,
            [],
            $this->getMockBuilder(BaseRepository::class)->disableOriginalConstructor()->getMock()
        );
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function hydrateValidDbRowIsEmpty(): void
    {
        $this->assertNull($this->hydrator->hydrate(
            HydratedByConstructor::class,
            [],
            $this->getMockBuilder(BaseRepository::class)->disableOriginalConstructor()->getMock()
        ));
    }
}
