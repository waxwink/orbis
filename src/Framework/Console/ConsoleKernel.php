<?php

namespace Waxwink\Orbis\Framework\Console;

use Psr\Container\ContainerInterface;
use Waxwink\Orbis\Configuration\ConfigurationInterface;
use Waxwink\Orbis\Console\Command;
use Waxwink\Orbis\Contracts\Bootable;
use Waxwink\Orbis\Contracts\CommandContainerInterface;

class ConsoleKernel implements Bootable
{
    public function __construct(
        protected ContainerInterface $container,
        protected ConfigurationInterface $configuration,
        protected CommandContainerInterface $commandContainer
    )
    {
    }

    /**
     * @throws CommandException
     */
    public function boot(): void
    {
        $this->loadCommands();
        $this->runCommand(array_slice($_SERVER['argv'], 1));
    }

    public function resolveCommand(string $commandName): Command
    {
        if (! $this->commandContainer->has($commandName)) {
            throw new CommandException("$commandName is not registered as a command");
        }

        return $this->container->get($this->commandContainer->get($commandName));
    }

    public function loadCommands()
    {
        $commands = $this->configuration->get("console.commands");
        if (!$commands) {
            return;
        }

        foreach ($commands as $command) {
            $this->commandContainer->register($command);
        }
    }

    public function getRegistered(): array
    {
        return $this->commandContainer->getRegistered();
    }

    /**
     * The input should be something like this: ["test:command","argument1", "argument2", "--option1=test", "-t"]
     * @throws CommandException
     */
    public function runCommand(array $commandArray)
    {
        $command = $this->resolveCommand(array_shift($commandArray));

        if (!is_callable($command)) {
            throw new CommandException(sprintf("Command %s should be callable and have an invoke message", get_class($command)));
        }

        [$arguments, $options] = $this->resolveArgumentsAndOptions($commandArray);

        $command->setOptions($options);

        ($command)(...$arguments);
    }

    protected function resolveArgumentsAndOptions(array $commandArray): array
    {
        $arguments = [];
        $options = [];

        foreach ($commandArray as $item) {
            if (preg_match("/--(.*)=(.*)/", $item, $matches)) {
                $options[$matches[1]] = $matches[2];
            } elseif (preg_match("/-(.*)/", $item, $matches)) {
                $options[$matches[1]] = true;
            } else {
                $arguments[] = $item;
            }
        }

        return [$arguments, $options];
    }
}
