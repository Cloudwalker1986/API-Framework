<?php

declare(strict_types=1);

namespace ApiCore\Utils;

final class HashMap implements Map
{
    private array $map = [];

    public function set(string $key, mixed $value): void
    {
        if (!$this->has($key)) {
            $this->map[$key] = $value;
        }
    }

    public function overwrite(string $key, mixed $value): void
    {
        $this->map[$key] = $value;
    }

    public function get(string $key): mixed
    {
        return $this->map[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->map);
    }

    public function flush(): void
    {
        $this->map = [];
    }
}
