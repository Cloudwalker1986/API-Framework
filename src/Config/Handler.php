<?php

declare(strict_types=1);

namespace ApiCore\Config;

use ApiCore\Config\Attribute\Configuration;
use ApiCore\Config\Attribute\Value;
use ApiCore\Dependency\Container;
use ApiCore\Dependency\Handler\HandlerInterface;
use ReflectionAttribute;
use ReflectionClass;

class Handler implements HandlerInterface
{
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
        $configuration = Container::getInstance()->get(BaseConfiguration::class);

        if ($configuration === null) {
            throw new \RuntimeException('Unable to load base configuration.');
        }

        foreach ($reflectionClass->getMethods() as $method) {

            /** @var ReflectionAttribute $reflectionAttribute */
            $reflectionAttribute = $method->getAttributes(Value::class)[0] ?? null;
            if ($reflectionAttribute) {
                /** @var Value $attribute */
                $attribute = $reflectionAttribute->newInstance();

                $instance->{$method->getName()}($configuration->get($attribute->getKey()));
            } else {

                $arguments = [];

                foreach ($method->getParameters() as $parameter) {
                    /** @var ReflectionAttribute $reflectionAttribute */
                    $reflectionAttribute = $parameter->getAttributes(Value::class)[0] ?? null;
                    if ($reflectionAttribute) {
                        /** @var Value $attribute */
                        $attribute = $reflectionAttribute->newInstance();
                        $arguments[] = $configuration->get($attribute->getKey());
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
