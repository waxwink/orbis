<?php

namespace Waxwink\Orbis\CommonCommands;

use Waxwink\Orbis\Console\Command;
use Waxwink\Orbis\Console\HasClimate;
use Waxwink\Orbis\EventDispatcher\EventDispatcher;

class EventsListCommand extends Command
{
    use HasClimate;

    protected const NAME = "events:list";

    public function __construct(protected EventDispatcher $eventDispatcher)
    {
    }

    public function __invoke()
    {
        $table = [];
        foreach ($this->eventDispatcher->getListeners() as $event => $listeners) {
            $row = [];
            $row["event"] = $event;
            foreach ($listeners as $listener) {
                $row["listeners"] .= $listener . "\n";
            }
            $table[] = $row;
        }

        $this->table($table);
    }
}
