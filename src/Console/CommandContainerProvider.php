<?php

namespace Waxwink\Orbis\Console;

use Waxwink\Orbis\Contracts\Bootable;
use Waxwink\Orbis\Contracts\CommandContainerInterface;
use Waxwink\Orbis\Contracts\ContainerInterface;

class CommandContainerProvider implements Bootable
{
    public function __construct(protected ContainerInterface $container)
    {
    }

    public function boot(): void
    {
        $this->container->set(CommandContainerInterface::class, CommandContainer::class);
    }
}
