<?php
declare(strict_types=1);

namespace ApiCore\Utils;

class UniqueCollection extends Collection
{
    public function add(mixed $value): CollectionInterface
    {
        if (!in_array($value, $this->elements, true)) {
            $this->elements[] = $value;
        }

        return $this;
    }
}
