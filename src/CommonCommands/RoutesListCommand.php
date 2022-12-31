<?php

namespace Waxwink\Orbis\CommonCommands;

use Symfony\Component\Routing\RouteCollection;
use Waxwink\Orbis\Console\Command;
use Waxwink\Orbis\Console\HasClimate;

class RoutesListCommand extends Command
{
    use HasClimate;

    protected const NAME = "routes:list";

    public function __construct(protected RouteCollection $routeCollection)
    {
    }

    public function __invoke()
    {
        $routes = ($this->routeCollection->all());
        $table = [];
        foreach ($routes as $key => $route) {
            $row["name"] = $key;
            $row["methods"] = json_encode($route->getMethods());
            $row["path"] = $route->getPath();
            $row["controller"] = $route->getDefault("_controller");
            $row["method"] = $route->getDefault("_method");
            $table[] = $row;
        }

        $this->table($table);
    }
}
