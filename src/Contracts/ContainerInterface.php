<?php

namespace Waxwink\Orbis\Contracts;

use Psr\Container\ContainerInterface as BaseContainerInterface;

interface ContainerInterface extends BaseContainerInterface
{
    public function set(string $id, $value): void;

    public function loadServices(array $services);

    public function call(string $class, string $method): mixed;

    public function all(): array;
}
