<?php

declare(strict_types=1);

namespace ApiCoreTest\Serializer;

use ApiCore\Dependency\Container;
use ApiCore\Serializer\SerializerInterface;
use ApiCoreTest\Serializer\Example\NormalizedAtPropertyExample;
use ApiCoreTest\Serializer\Example\SerializableExample;
use ApiCoreTest\Serializer\Example\SerializedNameWithConstructorExample;
use ApiCoreTest\Serializer\Example\SerializedNameWithSetterExample;
use JsonException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SerializerTest extends TestCase
{
    /**
     * @throws JsonException
     */
    #[Test]
    public function serialize(): void
    {
        /** @var SerializerInterface $serializer */
        $serializer = Container::getInstance()->get(SerializerInterface::class);
    
        $object = new SerializableExample();
        $object
            ->setAge(15)
            ->setCars(['BMW', 'AUDI', 'VW'])
            ->setUsername('Hello World')
            ->setCashFlow(4583.25);

        $expected = json_encode([
            'username' => 'Hello World',
            'age' => 15,
            'cashFlow' => 4583.25,
            'cars' => ['BMW', 'AUDI', 'VW']
        ],
            JSON_THROW_ON_ERROR
        );

        $this->assertEquals($expected, $serializer->serialize($object));
    }
    /**
     * @throws JsonException
     */
    #[Test]
    public function serializeWithAlias(): void
    {
        /** @var SerializerInterface $serializer */
        $serializer = Container::getInstance()->get(SerializerInterface::class);

        $object = new NormalizedAtPropertyExample();
        $object
            ->setAge(15)
            ->setCars(['BMW', 'AUDI', 'VW'])
            ->setUsername('Hello World')
            ->setCashFlow(4583.25);

        $expected = json_encode([
            'nameOfUser' => 'Hello World',
            'ageInYears' => 15,
            'howManyMoney' => 4583.25,
            'carItems' => ['BMW', 'AUDI', 'VW']
        ],
            JSON_THROW_ON_ERROR
        );

        $this->assertEquals($expected, $serializer->serialize($object));
    }

    /**
     * @throws JsonException
     */
    #[Test]
    public function deserialize(): void
    {
        /** @var SerializerInterface $serializer */
        $serializer = Container::getInstance()->get(SerializerInterface::class);
        $payload = json_encode([
            'username' => 'Hello World',
            'age' => 15,
            'cashFlow' => 4583.25,
            'cars' => ['BMW', 'AUDI', 'VW']
        ],
            JSON_THROW_ON_ERROR
        );

        $expected = new SerializableExample();
        $expected
            ->setAge(15)
            ->setCars(['BMW', 'AUDI', 'VW'])
            ->setUsername('Hello World')
            ->setCashFlow(4583.25);

        $this->assertEquals($expected, $serializer->deserialize($payload, SerializableExample::class));
    }

    /**
     * @throws JsonException
     */
    #[Test]
    #[DataProvider('dataProviderDeserializeWithAlias')]
    public function deserializeWithAlias(string $fqcn, object $expected): void
    {
        /** @var SerializerInterface $serializer */
        $serializer = Container::getInstance()->get(SerializerInterface::class);
        $payload = json_encode([
            'nameOfUser' => 'Hello World',
            'ageInYears' => 15,
            'howManyMoney' => 4583.25,
            'carItems' => ['BMW', 'AUDI', 'VW']
        ],
            JSON_THROW_ON_ERROR
        );

        $this->assertEquals($expected, $serializer->deserialize($payload, $fqcn));
    }
    public function dataProviderDeserializeWithAlias(): array
    {
        return [
            'Hydrate class via setters' => [
                'fqcn' => SerializedNameWithSetterExample::class,
                'expected' => (new SerializedNameWithSetterExample())
                    ->setAge(15)
                    ->setCars(['BMW', 'AUDI', 'VW'])
                    ->setUsername('Hello World')
                    ->setCashFlow(4583.25)
            ],
            'Hydrate class via Constructor' => [
                'fqcn' => SerializedNameWithConstructorExample::class,
                'expected' => (new SerializedNameWithConstructorExample(
                    username: 'Hello World',
                    age: 15,
                    cashFlow: 4583.25,
                    cars: ['BMW', 'AUDI', 'VW']
                ))
            ]
        ];
    }
}
