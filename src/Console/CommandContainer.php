<?php

namespace Waxwink\Orbis\Console;

use Waxwink\Orbis\Contracts\CommandContainerInterface;

class CommandContainer implements CommandContainerInterface
{
    protected array $registered = [];

    public function register(string $commandClassName): void
    {
        $this->registered[$commandClassName::name()] = $commandClassName;
    }

    public function getRegistered(): array
    {
        return $this->registered;
    }


    public function has(string $commandName): bool
    {
        return array_key_exists($commandName, $this->registered);
    }

    public function get(string $commandName): string
    {
        return $this->registered[$commandName];
    }
}
