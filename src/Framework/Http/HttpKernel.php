<?php

namespace Waxwink\Orbis\Framework\Http;

use Symfony\Component\HttpFoundation\Request;
use Waxwink\Orbis\Contracts\Bootable;
use Waxwink\Orbis\Contracts\ContainerInterface;

class HttpKernel implements Bootable
{
    public function __construct(protected HttpRequestHandler $handler, protected ContainerInterface $container)
    {
    }

    public function boot(): void
    {
        $request = $this->resolveRequest();
        $this->registerRequestInContainer($request);

        $response = $this->handler->handle($request);
        $response->send();
    }

    protected function resolveRequest(): Request
    {
        return Request::createFromGlobals();
    }


    private function registerRequestInContainer(Request $request): void
    {
        $this->container->set(Request::class, $request);
        $this->container->set('http.request', $request);
        $this->container->set('request', $request);
    }
}
