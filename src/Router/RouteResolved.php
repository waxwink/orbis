<?php

namespace Waxwink\Orbis\Router;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Waxwink\Orbis\Framework\Http\RequestResponse;

class RouteResolved
{
    public Route $route;

    public RequestResponse $requestResponse;

    /**
     * @param Route $route
     * @param RequestResponse $requestResponse
     */
    public function __construct(Route $route, RequestResponse $requestResponse)
    {
        $this->route = $route;
        $this->requestResponse = $requestResponse;
    }


    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->requestResponse->request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->requestResponse->response;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->route->getDefaults();
    }

    public function __get(string $key): mixed
    {
        return $this->route->getDefault($key);
    }

    public function __set(string $name, $value): void
    {
        $this->route->setDefault($name, $value);
    }

    public function __isset(string $name): bool
    {
        return $this->route->hasDefault($name);
    }
}
