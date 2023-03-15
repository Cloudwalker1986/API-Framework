<?php

declare(strict_types=1);

namespace ApiCoreTest\Dependency\Resolver\Example;

class ComplexFooBar
{
    private ?ComplexFoo $foo = null;

    private ?ComplexBar $bar = null;

    public function setProperties(ComplexFoo $foo, ComplexBar $bar): void
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

    public function getFoo(): ?ComplexFoo
    {
        return $this->foo;
    }

    public function getBar(): ?ComplexBar
    {
        return $this->bar;
    }
}
