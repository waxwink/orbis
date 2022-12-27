<?php

namespace Waxwink\Orbis\Configuration;

use Waxwink\Orbis\Contracts\Bootable;
use Waxwink\Orbis\Contracts\ContainerInterface;

class ConfigurationProvider implements Bootable
{
    public function __construct(protected ContainerInterface $container)
    {
    }

    public function boot(): void
    {
        $this->container->set(ConfigurationInterface::class, Configuration::class);
    }
}
