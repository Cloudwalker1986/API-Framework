<?php
declare(strict_types=1);

namespace ApiCoreTest\Dependency\Example;

class Foo
{
    public function get(): string
    {
        return 'I am Foo';
    }
}
