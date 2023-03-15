<?php

declare(strict_types=1);

namespace ApiCoreTest\Dependency\Resolver\Example;

class Bar
{
    private ?FooBar $fooBarr = null;

    public function getFooBarr(): ?FooBar
    {
        return $this->fooBarr;
    }

    public function setFooBarr(?FooBar $fooBarr): void
    {
        $this->fooBarr = $fooBarr;
    }

}
