<?php

namespace Waxwink\Orbis\ControllerCaller;

use Symfony\Component\HttpFoundation\Response;
use Waxwink\Orbis\Contracts\ContainerInterface;
use Waxwink\Orbis\Router\RouteResolved;

class CallController
{
    public function __construct(protected ContainerInterface $container)
    {
    }

    public function __invoke(RouteResolved $event): void
    {
        try {
            $controller = $event->_controller;
            $method = $event->_method;
        } catch (\Throwable $exception) {
            throw new ControllerException(sprintf(
                "Controller and method is not set for the path %s. %s",
                $event->route->getPath(),
                $exception->getMessage()
            ));
        }
        $result = $this->container->call($controller, $method);

        if ($result instanceof Response) {
            $event->requestResponse->response = $result;
        } elseif (is_array($result)) {
            $response = $event->getResponse();
            $response->setContent(json_encode($result, JSON_THROW_ON_ERROR))->setStatusCode(200);
            $response->headers->set('Content-Type', 'application/json');
        } elseif (is_string($result)) {
            $response = $event->getResponse();
            $response->setContent($result)->setStatusCode(200);
            $response->headers->set('Content-Type', 'text/html');
        } else {
            throw new ControllerException(sprintf("Controller %s::%s is not returning a valid output to be rendered", get_class($controller), $method));
        }
    }
}
