<?php

namespace Waxwink\Orbis\Contracts;

interface CommandContainerInterface
{
    public function register(string $commandClassName): void;
    public function getRegistered(): array;
    public function has(string $commandName): bool;

    public function get(string $commandName): string;
}
