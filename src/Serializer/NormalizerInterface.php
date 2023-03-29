<?php

declare(strict_types=1);

namespace ApiCore\Serializer;

interface NormalizerInterface
{
    /**
     * The denormalize function receives an array {$payload} and will convert it into a PHP object {$fqcn}
     */
    public function denormalize(array $payload, string $fqcn): object;

    /**
     * The normalize function receives and PHP object {$class} and will convert it into an array.
     */
    public function normalize(object $class): array;
}
