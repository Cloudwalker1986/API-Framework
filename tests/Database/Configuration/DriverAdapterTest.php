<?php

declare(strict_types=1);

namespace ApiCoreTest\Database\Configuration;

use ApiCore\Database\Configuration\AdapterDriverConfig;
use ApiCore\Database\Enum\Adapter;
use ApiCore\Database\Exception\UnknownDatabaseAdapterException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DriverAdapterTest extends TestCase
{
    #[Test]
    #[DataProvider('dataProviderForSetDriver')]
    public function setDriver(string $validDriver, Adapter $expected): void
    {
        $configuration = new AdapterDriverConfig();
        $configuration->setDriver($validDriver);

        $this->assertEquals($expected, $configuration->getDriver());
    }

    public static function dataProviderForSetDriver(): array
    {
        return [
            'valid case for mysqli' => [
                'validDriver' => 'mysqli',
                'expected' => Adapter::MYSQLI,
            ],
            'valid case for pdo' => [
                'validDriver' => 'pdo',
                'expected' => Adapter::PDO
            ]
        ];
    }

    #[Test]
    public function setDriverInvalid(): void
    {
        $this->expectException(UnknownDatabaseAdapterException::class);
        $this->expectExceptionMessage(
            'Your selected driver "invalid" is not supported. The following driver are supported "pdo, mysqli"'
        );

        $configuration = new AdapterDriverConfig();
        $configuration->setDriver('invalid');

    }
}
