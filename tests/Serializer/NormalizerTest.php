<?php

declare(strict_types=1);

namespace ApiCoreTest\Serializer;

use ApiCore\Dependency\Container;
use ApiCore\Serializer\NormalizerInterface;
use ApiCore\Serializer\SerializerInterface;
use ApiCoreTest\Serializer\Example\SerializeableExample;
use JsonException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class NormalizerTest extends TestCase
{
    /**
     * @throws JsonException
     */
    #[Test]
    public function serialize(): void
    {
        /** @var NormalizerInterface $serializer */
        $serializer = Container::getInstance()->get(NormalizerInterface::class);
    
        $object = new SerializeableExample();
        $object
            ->setAge(15)
            ->setCars(['BMW', 'AUDI', 'VW'])
            ->setUsername('Hello World')
            ->setCashFlow(4583.25);

        $expected = [
            'username' => 'Hello World',
            'age' => 15,
            'cashFlow' => 4583.25,
            'cars' => ['BMW', 'AUDI', 'VW']
        ];

        $this->assertEquals($expected, $serializer->normalize($object));
    }

    /**
     * @throws JsonException
     */
    #[Test]
    public function deserialize(): void
    {
        /** @var NormalizerInterface $serializer */
        $serializer = Container::getInstance()->get(NormalizerInterface::class);
        $payload = [
            'username' => 'Hello World',
            'age' => 15,
            'cashFlow' => 4583.25,
            'cars' => ['BMW', 'AUDI', 'VW']
        ];

        $expected = new SerializeableExample();
        $expected
            ->setAge(15)
            ->setCars(['BMW', 'AUDI', 'VW'])
            ->setUsername('Hello World')
            ->setCashFlow(4583.25);

        $this->assertEquals($expected, $serializer->denormalize($payload, SerializeableExample::class));
    }
}
