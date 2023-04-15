<?php

declare(strict_types=1);

namespace ApiCore\Config;

use ApiCore\Config\Attribute\Configuration;
use ApiCore\Config\Attribute\Value;
use ApiCore\Dependency\Container;
use ApiCore\Dependency\Handler\HandlerInterface;
use ApiCore\Dependency\Resolver\ClassResolver;
use ReflectionAttribute;
use ReflectionClass;

class Handler implements HandlerInterface
{
    public function __construct(private readonly Container $container)
    {
    }

    public function supports(?object $instance, ReflectionClass $reflectionClass): bool
    {
        return !empty($reflectionClass->getAttributes(Configuration::class));
    }

    /**
     * @throws \ReflectionException
     */
    public function handle(?object $instance, ReflectionClass $reflectionClass): ?object
    {
        if ($instance !== null) {
            return $instance;
        }

        $instance = $reflectionClass->newInstance();

        /** @var BaseConfiguration $configuration */
        $configuration = $this->container->get(BaseConfiguration::class);

        if ($configuration === null) {
            throw new \RuntimeException('Unable to load base configuration.');
        }

        foreach ($reflectionClass->getMethods() as $method) {

            /** @var ReflectionAttribute $reflectionAttribute */
            $reflectionAttribute = $method->getAttributes(Value::class)[0] ?? null;
            if ($reflectionAttribute) {
                /** @var Value $attribute */
                $attribute = $reflectionAttribute->newInstance();

                $value = $configuration->get($attribute->getKey());

                $instance->{$method->getName()}($value ?? $attribute->getDefault());
            } else {

                $arguments = [];

                foreach ($method->getParameters() as $parameter) {
                    /** @var ReflectionAttribute $reflectionAttribute */
                    $reflectionAttribute = $parameter->getAttributes(Value::class)[0] ?? null;
                    if ($reflectionAttribute) {
                        /** @var Value $attribute */
                        $attribute = $reflectionAttribute->newInstance();

                        $value = $configuration->get($attribute->getKey());

                        $arguments[] = $value ?? $attribute->getDefault();
                    }
                }

                if (!empty($arguments)) {
                    call_user_func_array([$instance, $method->getName()], $arguments);
                }
            }
        }

        return $instance;
    }
}
