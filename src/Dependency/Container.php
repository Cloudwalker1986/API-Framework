<?php

declare(strict_types=1);

namespace ApiCore\Dependency;

use ApiCore\Dependency\Resolver\ClassResolver;
use ApiCore\Utils\HashMap;
use ApiCore\Utils\Map;

class Container
{
    private static ?Container $instance = null;

    private ClassResolver $classResolver;

    private function __construct(private readonly Map $container)
    {
        $this->classResolver = ClassResolver::build($this);
    }

    public function set(string $key, object $object): void
    {
        $this->container->set($key, $object);
    }

    /**
     * @param <T> $key
     * @return <T>|object|null
     */
    public function get(string $key): ?object
    {
        if ($this->container->has($key)) {
            return $this->container->get($key);
        }

        $class = $this->classResolver->resolve($key);

        if ($class) {
            $this->container->set($key, $class);
        }

        return $class;
    }

    public function flush(): void
    {
        $this->container->flush();
    }

    public function erase(): void
    {
        $this->flush();
        self::$instance = null;
    }

    public static function getInstance(): Container
    {
        if (self::$instance === null) {
            self::$instance = new self(new HashMap());
        }

        return self::$instance;
    }
}
