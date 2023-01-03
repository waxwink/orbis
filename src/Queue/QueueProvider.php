<?php

namespace Waxwink\Orbis\Queue;

use Waxwink\Orbis\Contracts\Bootable;
use Waxwink\Orbis\Contracts\CommandContainerInterface;

class QueueProvider implements Bootable
{
    public function __construct(protected CommandContainerInterface $commandContainer)
    {
    }

    public function boot(): void
    {
        $this->commandContainer->register(Worker::class);
    }
}
