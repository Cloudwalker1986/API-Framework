<?php
declare(strict_types=1);

namespace ApiCore\Utils;

class Collection implements CollectionInterface
{
    protected array $elements = [];

    public function add(mixed $value): CollectionInterface
    {
        $this->elements[] = $value;

        return $this;
    }

    public function current(): mixed
    {
        return current($this->elements);
    }

    public function next(): void
    {
        next($this->elements);
    }

    public function key(): string|int|null
    {
        return key($this->elements);
    }

    public function valid(): bool
    {
        return $this->current() !== false;
    }

    public function rewind(): void
    {
        reset($this->elements);
    }
}
