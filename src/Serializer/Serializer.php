<?php

declare(strict_types=1);

namespace ApiCore\Serializer;

use ApiCore\Dependency\Attribute\InterfaceTag;
use JsonException;
use ReflectionException;

class Serializer implements SerializerInterface
{
    public function __construct(#[InterfaceTag(Normalizer::class)]private readonly NormalizerInterface $normalizer)
    {
    }

    /**
     * @inheritDoc
     * @throws JsonException
     * @throws ReflectionException
     */
    public function deserialize(string $payload, string $fqcn): object
    {
        return $this->normalizer->denormalize(
            json_decode($payload, true, 512, JSON_THROW_ON_ERROR),
            $fqcn
        );
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function serialize(object $class): string
    {
        $data = $this->normalizer->normalize($class);

        return json_encode($data, JSON_THROW_ON_ERROR);
    }
}
