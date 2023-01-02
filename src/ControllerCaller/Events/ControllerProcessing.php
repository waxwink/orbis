<?php

namespace Waxwink\Orbis\ControllerCaller\Events;

use Symfony\Component\Routing\Route;

class ControllerProcessing
{
    public Route $route;
    public string $controller;
    public string $method;

    /**
     * @param Route $route
     * @param string $controller
     * @param string $method
     */
    public function __construct(Route $route, string $controller, string $method)
    {
        $this->route = $route;
        $this->controller = $controller;
        $this->method = $method;
    }
}
