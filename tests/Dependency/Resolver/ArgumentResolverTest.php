<?php

declare(strict_types=1);

namespace ApiCoreTest\Dependency\Resolver;

use ApiCore\Dependency\Container;
use ApiCoreTest\Dependency\Resolver\Example\Bar;
use ApiCoreTest\Dependency\Resolver\Example\ComplexBar;
use ApiCoreTest\Dependency\Resolver\Example\ComplexFoo;
use ApiCoreTest\Dependency\Resolver\Example\ComplexFooBar;
use ApiCoreTest\Dependency\Resolver\Example\Foo;
use ApiCoreTest\Dependency\Resolver\Example\FooBar;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ArgumentResolverTest extends TestCase
{
    #[Test]
    public function loadSimpleClass(): void
    {
        $foo = Container::getInstance()->get(Foo::class);

        $this->assertInstanceOf(Foo::class, $foo);
        $this->assertInstanceOf(Bar::class, $foo->getBar());
        $this->assertInstanceOf(FooBar::class, $foo->getBar()->getFooBarr());
    }

    #[Test]
    public function loadComplexClass(): void
    {
        /** @var ComplexFooBar $fooBar */
        $fooBar = Container::getInstance()->get(ComplexFooBar::class);

        $this->assertInstanceOf(ComplexFoo::class, $fooBar->getFoo());
        $this->assertInstanceOf(ComplexBar::class, $fooBar->getBar());
    }
}
