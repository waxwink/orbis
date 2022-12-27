<?php

namespace Waxwink\Orbis\Framework;

use Waxwink\Orbis\Application\Application;
use Waxwink\Orbis\Application\KernelManager;

class OrbisApplication extends Application
{
    protected function makeKernelManager($arguments): KernelManager
    {
        return new OrbisKernelManager($arguments);
    }
}
