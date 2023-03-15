<?php

declare(strict_types=1);

namespace ApiCoreTest\Dependency;

use ApiCore\Dependency\Container;
use ApiCoreTest\Dependency\Example\Foo;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    private Container $container;

    public function setUp(): void
    {
        parent::setUp();
        $this->container = Container::getInstance();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->container->erase();
    }

    #[Test]
    public function validSet(): void
    {
        $this->container->set(Foo::class, new Foo());

        $this->assertInstanceOf(Foo::class, $this->container->get(Foo::class));
        $this->assertEquals('I am Foo', $this->container->get(Foo::class)->get());

    }

    #[Test]
    public function invalidGt(): void
    {
        $this->assertNull($this->container->get('HelloWorld'));
    }
}
