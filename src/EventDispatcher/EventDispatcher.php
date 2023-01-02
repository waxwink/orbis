<?php

namespace Waxwink\Orbis\EventDispatcher;

use Psr\Container\ContainerInterface;
use Waxwink\Orbis\Contracts\Queueable;
use Waxwink\Orbis\Contracts\QueueInterface;

class EventDispatcher implements EventDispatcherInterface
{
    protected array $listeners = [];

    public function __construct(protected ContainerInterface $container, protected ?QueueInterface $queueConnection = null)
    {
    }

    public function dispatch(object $event): object
    {
        if (!array_key_exists(get_class($event), $this->listeners)) {
            return $event;
        }

        foreach ($this->listeners[get_class($event)] as $listener) {
            $listener = $this->container->get($listener);
            if ($listener instanceof Queueable) {
                $this->queueListener($listener, $event);
                continue;
            }
            $listener($event);
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

    private function queueListener(Queueable $listener, object $event = null): void
    {
        // Maybe the queue connection is not loaded as it is not mandatory
        if (!$this->queueConnection) {
            return;
        }

        $this->queueConnection->addJob($listener, [$event]);
    }
}
