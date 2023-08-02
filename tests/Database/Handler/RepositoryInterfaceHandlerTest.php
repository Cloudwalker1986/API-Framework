<?php

declare(strict_types=1);

namespace ApiCoreTest\Database\Handler;

use ApiCore\Dependency\Container;
use ApiCoreTest\Database\Handler\Example\ExtendedRepository;
use ApiCoreTest\Database\Handler\Example\SimpleCrudRepository;
use ApiCoreTest\Database\Handler\Example\SimpleReaderRepository;
use ApiCoreTest\Database\Handler\Example\SimpleRepository;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RepositoryInterfaceHandlerTest extends TestCase
{
    #[Test]
    #[DataProvider('dataProviderGenerate')]
    public function generate(string $className, array $exactExpected): void
    {
        $repository = Container::getInstance()->get($className);

        $this->assertInstanceOf($className, $repository);
        $this->assertContainsEquals($exactExpected, [get_class_methods($repository)]);
    }

    public static function dataProviderGenerate(): array
    {
        return [
            'Simple repository interface' => [
                'className' => SimpleRepository::class,
                'exactExpected' => ['findById', 'findAll', 'findAllBySearch']
            ],
            'extended repository' => [
                'className' => ExtendedRepository::class,
                'exactExpected' => ['findById', 'findAll', 'findAllBySearch', 'findByCriteria', 'findOneByCriteriaOrNull']
            ],
            'simple reader repository' => [
                'className' => SimpleReaderRepository::class,
                'exactExpected' => ['findByCriteria', 'findOneByCriteriaOrNull']
            ],
            'simple crud repository' => [
                'className' => SimpleCrudRepository::class,
                'exactExpected' => ['persists', 'delete']
            ]
        ];
    }
}
