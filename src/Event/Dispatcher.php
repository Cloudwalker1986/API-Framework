<?php

declare(strict_types=1);

namespace ApiCore\Event;

use ApiCore\Dependency\Container;
use ApiCore\Event\Exception\NoSubscriberRegistered;
use ApiCore\Utils\Collection;
use ApiCore\Utils\CollectionInterface;
use ApiCore\Utils\Map;

class Dispatcher
{
    /**
     * @param Map<string, CollectionInterface> $map
     */
    public function __construct(private readonly Map $map, private readonly Container $container)
    {
    }

    public function register(string $eventName, string $fqcnSubscriber): void
    {
        if ($this->map->has($eventName)) {
            /** @var CollectionInterface $subscribers */
            $subscribers = $this->map->get($eventName);
            $subscribers->add($fqcnSubscriber);
            return;
        }

        $collection = new Collection();
        $collection->add($fqcnSubscriber);
        $this->map->set($eventName, $collection);
    }

    public function dispatch(string $eventName, object $payload): void
    {
        if (!$this->map->has($eventName)) {
            throw new NoSubscriberRegistered($eventName);
        }

        /** @var CollectionInterface $subscribers */
        $subscribers = $this->map->get($eventName);

        foreach ($subscribers as $subscriber) {
            try {
                $listener = $this->container->get($subscriber->getSubscriber());
                $listener?->{$subscriber->getMethod()}($payload);
            } catch (\Throwable $e) {
                // for now no handling required
                continue;
            }
        }
    }
}
