<?php

declare(strict_types=1);

namespace ApiCoreTest\Event;

use ApiCore\Dependency\Container;
use ApiCore\Event\Dispatcher;
use ApiCore\Event\Exception\NoSubscriberRegistered;
use ApiCoreTest\Event\Example\EventSubscriber;
use ApiCoreTest\Event\Example\Payload;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DispatcherTest extends TestCase
{
    #[Test]
    public function dispatchSuccess(): void
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = Container::getInstance()->get(Dispatcher::class);

        $dispatcher->register(EventSubscriber::EVENT_NAME, EventSubscriber::class);

        $payload = new Payload();
        $dispatcher->dispatch(EventSubscriber::EVENT_NAME, $payload);
        $this->assertEquals(3, $payload->getI());
        $dispatcher->dispatch(EventSubscriber::EVENT_NAME, $payload);
        $this->assertEquals(4, $payload->getI());
    }

    #[Test]
    public function dispatchFailed(): void
    {
        $this->expectException(NoSubscriberRegistered::class);
        $this->expectExceptionMessage('Event of "Event does not exists" has no subscriber assigned.');

        /** @var Dispatcher $dispatcher */
        $dispatcher = Container::getInstance()->get(Dispatcher::class);

        $dispatcher->dispatch('Event does not exists', new class{});
    }
}
