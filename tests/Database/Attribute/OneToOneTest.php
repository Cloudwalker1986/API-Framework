<?php

declare(strict_types=1);

namespace ApiCoreTest\Database\Attribute;

use ApiCore\Database\Attribute\OneToOne;
use ApiCoreTest\Database\Attribute\Example\OneToOneEntityBar;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class OneToOneTest extends TestCase
{
    #[Test]
    public function validOneToOneMapping(): void
    {
        $this->expectExceptionNotThrown(function () {
            $reflection = new \ReflectionClass(OneToOneEntityBar::class);

            $reflection->getProperty('foo')->getAttributes(OneToOne::class)[0]->newInstance();
        });
    }

    private function expectExceptionNotThrown(callable $function): void
    {
        try {
            $function();
            $this->assertTrue(true);
        } catch (\Throwable $e) {
            $this->fail('Unexpected exception occurred.' . $e->getMessage());
        }
    }
}
