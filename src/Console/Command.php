<?php

namespace Waxwink\Orbis\Console;

abstract class Command
{
    protected $options = [];

    protected const NAME = "name";

    public static function name()
    {
        return static::NAME;
    }

    public function setOptions(array $options): Command
    {
        $this->options = $options;
        return $this;
    }

    public function __get(string $name)
    {
        return $this->options[$name] ?? null;
    }


    public function __set(string $name, $value): void
    {
        $this->options[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->options);
    }
}
