<?php
declare(strict_types=1);

namespace ApiCore\Utils;

class TypedCollection extends Collection
{
    private mixed $type = null;

    public function add(mixed $value): CollectionInterface
    {
        if ($this->type === null) {
            $this->type = gettype($value);
            parent::add($value);
        } elseif (gettype($value) === $this->type) {
            parent::add($value);
        }

        return $this;
    }
}
