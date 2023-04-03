<?php

declare(strict_types=1);

namespace ApiCore\Event;

use ApiCore\Dependency\Attribute\InterfaceTag;
use ApiCore\Dependency\Container;
use ApiCore\Event\Attribute\Subscribe;
use ApiCore\Event\Exception\NoSubscriberRegistered;
use ApiCore\Utils\Collection;
use ApiCore\Utils\CollectionInterface;
use ApiCore\Utils\HashMap;
use ApiCore\Utils\Map;
use ReflectionClass;

class Dispatcher
{
    /**
     * @param Map<string, CollectionInterface> $map
     */
    public function __construct(
        #[InterfaceTag(HashMap::class)]
        private readonly Map $map,
        private readonly Container $container
    ) {
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
                $reflectionClass = new \ReflectionClass($subscriber);
                $subscriberObject = $this->container->get($subscriber);

                if ($subscriberObject === null) {
                    // @todo log here
                    continue;
                }

                $this->dispatchMethod($reflectionClass, $eventName, $subscriberObject, $payload);
            } catch (\Throwable $e) {
                // @todo log here
                // for now no handling required
                continue;
            }
        }
    }

    private function isMethodTagged(\ReflectionMethod $method, string $eventName): bool
    {
        foreach ($method->getAttributes() as $methodAttribute) {
            $attribute = $methodAttribute->newInstance();
            if ($attribute instanceof Subscribe && $attribute->getEventName() === $eventName) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param string $eventName
     * @param object $subscriberObject
     * @param object $payload
     *
     * @return void
     */
    private function dispatchMethod(
        ReflectionClass $reflectionClass,
        string $eventName,
        object $subscriberObject,
        object $payload
    ): void {
        foreach ($reflectionClass->getMethods() as $method) {
            if ($this->isMethodTagged($method, $eventName)) {
                $subscriberObject->{$method->getName()}($payload);
            }
        }
    }
}
