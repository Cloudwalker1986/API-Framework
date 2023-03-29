<?php

declare(strict_types=1);

namespace ApiCore\Serializer;

use JsonException;

interface SerializerInterface
{
    /**
     * The deserialize function receives and a JSON string {$payload} and will convert it into a PHP object {$fqcn}.
     */
    public function deserialize(string $payload, string $fqcn): object;

    /**
     * The serialize function receives and PHP object {$class} and will convert it into JSON string.
     */
    public function serialize(object $class): string;
}
