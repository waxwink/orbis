<?php

namespace Waxwink\Orbis\EventDispatcher;

use App\Core\Contracts\Bootable;
use App\Core\Contracts\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface as PsrEventDispatcherInterface;

class EventDispatcherProvider implements Bootable
{
    public function __construct(protected ContainerInterface $container)
    {
    }

    public function boot(): void
    {
        $this->container->set(PsrEventDispatcherInterface::class, EventDispatcher::class);
        $this->container->set(EventDispatcherInterface::class, EventDispatcher::class);
    }
}
