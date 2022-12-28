<?php

namespace Waxwink\Orbis\CommonCommands;

use Waxwink\Orbis\Console\CommandContainer;
use Waxwink\Orbis\Contracts\Bootable;

class CommonCommandsProvider implements Bootable
{
    public function __construct(protected CommandContainer $commandContainer)
    {
    }

    public function boot(): void
    {
        $this->commandContainer->register(ContainerList::class);
    }
}
