<?php

declare(strict_types=1);

namespace ApiCore\Utils;

interface Map
{
    public function set(string $key, mixed $value): void;

    public function overwrite(string $key, mixed $value): void;

    public function get(string $key): mixed;

    public function has(string $key): bool;

    public function flush(): void;
}
