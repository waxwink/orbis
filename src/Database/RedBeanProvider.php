<?php

namespace Waxwink\Orbis\Database;

use RedBeanPHP\R;
use Waxwink\Orbis\Configuration\ConfigurationInterface;
use Waxwink\Orbis\Contracts\Bootable;

class RedBeanProvider implements Bootable
{
    public function __construct(protected ConfigurationInterface $configuration)
    {
    }

    public function boot(): void
    {
        $keys = [];
        foreach ($this->configuration->get('database') as $key => $database) {
            $host = $database['host'];
            $db = $database['db'];
            $username = $database['username'];
            $password = $database['password'];
            $keys[] = $key;
            R::addDatabase($key, sprintf('mysql:host=%s;dbname=%s', $host, $db), $username, $password);
        }
        R::selectDatabase($keys[0]);
    }
}
