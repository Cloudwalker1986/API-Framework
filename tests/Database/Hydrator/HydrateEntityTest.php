<?php

declare(strict_types=1);

namespace ApiCoreTest\Database\Hydrator;

use ApiCore\Database\Exception\ClassNoTaggedAsEntityException;
use ApiCore\Database\Hydrator\Exception\UnkownValueForParameterException;
use ApiCore\Database\Hydrator\HydrateEntity;
use ApiCore\Logger\Enum\LogFormat;
use ApiCore\Logger\Logger;
use ApiCore\Logger\Writer\NullLoggerWriter;
use ApiCoreTest\Database\Hydrator\Example\HydratedByConstructor;
use ApiCoreTest\Database\Hydrator\Example\HydratedBySetters;
use ApiCoreTest\Database\Hydrator\Example\NoEntityTaggedClass;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
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
            ]
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
            ]
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
            ]
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

        $this->hydrator->hydrate(NoEntityTaggedClass::class, []);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function hydrateValidDbRowIsEmpty(): void
    {
        $this->assertNull($this->hydrator->hydrate(HydratedByConstructor::class, []));
    }
}
