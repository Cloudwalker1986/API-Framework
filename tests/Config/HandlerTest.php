<?php

declare(strict_types=1);

namespace ApiCoreTest\Config;

use ApiCore\Config\BaseConfiguration;
use ApiCore\Config\Yaml\Configuration;
use ApiCore\Dependency\Container;
use ApiCoreTest\Config\Example\ExampleConfiguration;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handle(): void
    {
        define('APPLICATION_CONFIG', __DIR__ . DIRECTORY_SEPARATOR . 'Example' . DIRECTORY_SEPARATOR . 'config.yaml');

        Container::getInstance()->get(Configuration::class);

        /** @var ExampleConfiguration $config */
        $config = Container::getInstance()->get(ExampleConfiguration::class);

        $this->assertEquals('Hello World', $config->getValue());
        $this->assertEquals(['Hello', 'World'], $config->getItems());
        $this->assertEquals(3, $config->getIntVal());
        $this->assertInstanceOf(Configuration::class, Container::getInstance()->get(BaseConfiguration::class));
    }
}
