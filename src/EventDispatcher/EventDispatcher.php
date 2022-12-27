<?php

namespace Waxwink\Orbis\EventDispatcher;

use Psr\Container\ContainerInterface;

class EventDispatcher implements EventDispatcherInterface
{
    protected array $listeners = [];

    public function __construct(protected ContainerInterface $container)
    {
    }

    public function dispatch(object $event): object
    {
        if (array_key_exists(get_class($event), $this->listeners)) {
            $listeners = $this->listeners[get_class($event)];
            foreach ($listeners as $listener) {
                $listener = $this->container->get($listener);
                $listener($event);
            }
        }
        return $event;
    }

    public function addListener(string $event, string $listener): void
    {
        $this->listeners[$event][] = $listener;
    }

    public function getListeners(): array
    {
        return $this->listeners;
    }
}
