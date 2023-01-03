<?php

namespace Waxwink\Orbis\Queue;

use Waxwink\Orbis\Console\Command;

class Worker extends Command
{
    public function __construct(protected QueueConnection $queueConnection)
    {
    }

    protected const NAME = "queue:work";

    public function __invoke()
    {
        while (true) {
            $queueItem = $this->queueConnection->nextJob();
            if (!$queueItem) {
                sleep(1);
                continue;
            }

            $listener = $queueItem->getJob();
            if (! is_callable($listener)) {
                throw new \RuntimeException(sprintf("Listener %s is not callable", get_class($listener)));
            }
            $listener(...$queueItem->getInputs());
            sleep(1);
        }
    }
}
