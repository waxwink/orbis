<?php

namespace Waxwink\Orbis\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface as PsrEventDispatcherInterface;

interface EventDispatcherInterface extends PsrEventDispatcherInterface
{
    public function addListener(string $event, string $listener): void;
}
