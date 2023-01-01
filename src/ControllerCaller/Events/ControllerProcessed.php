<?php

namespace Waxwink\Orbis\ControllerCaller\Events;

class ControllerProcessed
{
    public mixed $result;

    public object $controller;

    public function __construct(mixed $result, object $controller)
    {
        $this->result = $result;
        $this->controller = $controller;
    }


}