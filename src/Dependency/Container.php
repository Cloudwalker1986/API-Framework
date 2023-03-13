<?php

declare(strict_types=1);

namespace ApiCore\Dependency;

use ApiCore\Utils\HashMap;
use ApiCore\Utils\Map;

class Container
{
    private static ?Container $instance = null;

    private function __construct(private readonly Map $container)
    {

    }

    public function set(string $key, object $object): void
    {
        $this->container->set($key, $object);
    }

    public function get(string $key): ?object
    {
        if ($this->container->has($key)) {
            return $this->container->get($key);
        }
        //@todo load here the resolver!

        return null;
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
