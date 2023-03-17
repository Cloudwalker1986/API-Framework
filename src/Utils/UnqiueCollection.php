<?php
declare(strict_types=1);

namespace ApiCore\Utils;

class UniqueCollection extends Collection
{
    public function add(mixed $value): void
    {
        if (!in_array($value, $this->elements, true)) {
            $this->elements[] = $value;
        }
    }
}
