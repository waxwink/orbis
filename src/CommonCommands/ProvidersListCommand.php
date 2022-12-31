<?php

namespace Waxwink\Orbis\CommonCommands;

use Waxwink\Orbis\Console\Command;
use Waxwink\Orbis\Console\HasClimate;
use Waxwink\Orbis\Framework\OrbisKernelManager;

class ProvidersListCommand extends Command
{
    use HasClimate;

    public function __construct(protected OrbisKernelManager $orbisKernelManager)
    {
    }

    public function __invoke($mode)
    {
        $table = [];

        foreach ($this->orbisKernelManager->resolveProviders($mode, $this->env ?? 'dev') as $provider) {
            $row = [];
            $row['Provider'] = $provider;
            $table[] = $row;
        }

        $this->table($table);
    }
}
