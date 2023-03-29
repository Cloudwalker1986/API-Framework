<?php

declare(strict_types=1);

namespace ApiCore\Serializer;

use ApiCore\Dependency\Resolver\ClassResolver;
use ApiCore\Serializer\Attribute\SerializedName;
use ReflectionClass;
use ReflectionException;

class Normalizer implements NormalizerInterface
{
    /**
     * @inheritDoc
     * @throws \ReflectionException
     */
    public function denormalize(array $payload, string $fqcn): object
    {
        $reflectionClass = new ReflectionClass($fqcn);

        return $this->hydrateWithConstructor($reflectionClass, $payload)
            ?? $this->hydrateWithSetters($reflectionClass, $payload);
    }

    /**
     * @inheritDoc
     */
    public function normalize(object $class): array
    {
        $reflectionClass = new ReflectionClass($class);

        $data = [];

        foreach ($reflectionClass->getProperties() as $property) {

            $serializedNamedAttribute = $property->getAttributes(SerializedName::class)[0] ?? null;

            $name = $property->getName();

            if ($serializedNamedAttribute !== null) {
                /** @var SerializedName $attribute */
                $attribute = $serializedNamedAttribute->newInstance();
                $name = $attribute->getSerializedName();
            }

            $data[$name] = $property->getValue($class);
        }

        return $data;
    }

    /**
     * @throws ReflectionException
     */
    private function hydrateWithConstructor(ReflectionClass $reflectionClass, array $payload): ?object
    {
        $constructorParameters = $reflectionClass->getConstructor()?->getParameters();

        $arguments = [];
        $instance = null;
        if (!empty($constructorParameters)) {
            foreach($constructorParameters as $parameter) {
                $serializedNamedAttribute = $parameter->getAttributes(SerializedName::class)[0] ?? null;

                $name = $parameter->getName();
                if ($serializedNamedAttribute !== null) {
                    /** @var SerializedName $attribute */
                    $attribute = $serializedNamedAttribute->newInstance();
                    $name = $attribute->getSerializedName();
                }

                $arguments[] = $payload[$name] ?? null;
            }

            $instance = $reflectionClass->newInstanceArgs($arguments);
        }

        return $instance;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param array $payload
     * @return mixed
     * @throws ReflectionException
     */
    protected function hydrateWithSetters(ReflectionClass $reflectionClass, array $payload): object
    {
        $instance = $reflectionClass->newInstance();
        foreach ($reflectionClass->getMethods() as $method) {
            if ($method->isPublic() && str_starts_with($method->getName(), ClassResolver::METHOD_SETTER)) {
                $arguments = [];
                foreach ($method->getParameters() as $methodParameter) {

                    $serializedNamedAttribute = $methodParameter->getAttributes(SerializedName::class)[0] ?? null;

                    $name = $methodParameter->getName();
                    if ($serializedNamedAttribute !== null) {
                        /** @var SerializedName $attribute */
                        $attribute = $serializedNamedAttribute->newInstance();
                        $name = $attribute->getSerializedName();
                    }

                    $arguments[] = $payload[$name] ?? null;
                }
                call_user_func_array([$instance, $method->getName()], $arguments);
            }
        }
        return $instance;
    }
}
