<?php

namespace Waxwink\Orbis\Configuration;

use Waxwink\Orbis\Contracts\Bootable;
use Waxwink\Orbis\Contracts\ContainerInterface;

class ConfigurationServiceProvider implements Bootable
{
    public function __construct(protected ContainerInterface $container)
    {
    }

    public function boot(): void
    {
        $this->container->set(\Waxwink\Orbis\Contracts\Configuration::class, Configuration::class);
    }
}
