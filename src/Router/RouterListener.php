<?php

namespace Waxwink\Orbis\Router;

use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Waxwink\Orbis\Contracts\ContainerInterface;
use Waxwink\Orbis\Framework\Http\RequestResponse;

class RouterListener
{
    public function __construct(
        protected UrlMatcher               $urlMatcher,
        protected RequestContext           $requestContext,
        protected ContainerInterface       $container,
        protected RouteCollection          $routeCollection,
        protected EventDispatcherInterface $eventDispatcher
    )
    {
    }

    public function __invoke(RequestResponse $requestResponse): void
    {
        $request = $requestResponse->getRequest();
        $this->requestContext->fromRequest($request);
        try {
            $res = $this->urlMatcher->matchRequest($request);
        } catch (\Throwable $exception) {
            throw new RouterException("Route not found. " . $exception->getMessage());
        }

        foreach ($res as $key => $value) {
            $this->container->set($key, $value);
        }

        $route = $this->routeCollection->get($res['_route']);
        $this->container->set(Route::class, $route);

        $this->eventDispatcher->dispatch(new RouteResolved($route, $requestResponse));
    }
}
