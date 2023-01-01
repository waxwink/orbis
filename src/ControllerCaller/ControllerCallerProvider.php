<?php

namespace Waxwink\Orbis\ControllerCaller;

use Waxwink\Orbis\Contracts\Bootable;
use Waxwink\Orbis\EventDispatcher\EventDispatcherInterface;
use Waxwink\Orbis\Router\RouteResolved;

class ControllerCallerProvider implements Bootable
{
    public function __construct(protected EventDispatcherInterface $eventDispatcher)
    {
    }

    public function boot(): void
    {
        $this->eventDispatcher->addListener(RouteResolved::class, CallController::class);
    }
}
