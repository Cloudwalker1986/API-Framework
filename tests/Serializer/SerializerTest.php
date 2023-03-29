<?php

declare(strict_types=1);

namespace ApiCoreTest\Serializer;

use ApiCore\Dependency\Container;
use ApiCore\Serializer\SerializerInterface;
use ApiCoreTest\Serializer\Example\SerializeableExample;
use JsonException;
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
    
        $object = new SerializeableExample();
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

        $expected = new SerializeableExample();
        $expected
            ->setAge(15)
            ->setCars(['BMW', 'AUDI', 'VW'])
            ->setUsername('Hello World')
            ->setCashFlow(4583.25);

        $this->assertEquals($expected, $serializer->deserialize($payload, SerializeableExample::class));
    }
}
