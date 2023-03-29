<?php

declare(strict_types=1);

namespace ApiCore\Serializer;

use ApiCore\Dependency\Resolver\ClassResolver;
use ReflectionClass;

class Normalizer implements NormalizerInterface
{
    /**
     * @inheritDoc
     */
    public function denormalize(array $payload, string $fqcn): object
    {
        $reflectionClass = new ReflectionClass($fqcn);

        $constructorParameters = $reflectionClass->getConstructor()?->getParameters();

        $arguments = [];
        $instance = null;
        if (!empty($constructorParameters)) {
            foreach($constructorParameters as $parameter) {
                $arguments[] = $payload[$parameter->getName()] ?? null;
            }

            $instance = $reflectionClass->newInstance($arguments);
        }

        if ($instance === null) {
            $instance = $reflectionClass->newInstance();
            foreach ($reflectionClass->getMethods() as $method) {
                if ($method->isPublic() && str_starts_with($method->getName(), ClassResolver::METHOD_SETTER)) {
                    $arguments = [];
                    foreach ($method->getParameters() as $parameter) {
                        $arguments[] = $payload[$parameter->getName()] ?? null;
                    }
                    call_user_func_array([$instance, $method->getName()], $arguments);
                }
            }
        }

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function normalize(object $class): array
    {
        $reflectionClass = new \ReflectionClass($class);

        $data = [];

        foreach ($reflectionClass->getProperties() as $property) {
            $data[$property->getName()] = $property->getValue($class);
        }

        return $data;
    }
}
