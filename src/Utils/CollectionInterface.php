<?php

declare(strict_types=1);

namespace ApiCore\Utils;

interface CollectionInterface extends \Iterator
{
    /**
     * Adds the value to the collection
     * @param mixed $value
     * @return void
     */
    public function add(mixed $value): void;
}
