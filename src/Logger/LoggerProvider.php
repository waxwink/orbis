<?php

namespace Waxwink\Orbis\Logger;

use Waxwink\Orbis\Configuration\ConfigurationInterface;
use Waxwink\Orbis\Contracts\Bootable;
use Waxwink\Orbis\Contracts\ContainerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\ResettableInterface;
use Psr\Log\LoggerInterface;

class LoggerProvider implements Bootable
{
    public function __construct(protected ContainerInterface $container, protected ConfigurationInterface $configuration)
    {
    }

    public function boot(): void
    {
        $path = $this->configuration->get("app.log.path") ?? $this->configuration->getBasePath() . "/var/logs/";
        $path .= "/logs.log";
        $logger = new Logger('storage');
        $logger->pushHandler(new StreamHandler(removeDuplicateSlashes($path), Level::Warning));

        $this->container->set(LoggerInterface::class, $logger);
        $this->container->set(ResettableInterface::class, $logger);
    }
}
