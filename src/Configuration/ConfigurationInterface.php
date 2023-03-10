<?php

namespace Waxwink\Orbis\Configuration;

interface ConfigurationInterface
{
    public function get(string $string, mixed $default = null): mixed;

    public function getEnv(): string;

    public function isDebug(): bool;

    public function getBasePath(): mixed;

    public function getConfigPath(): string;

    public function getConfig(): array;
}
