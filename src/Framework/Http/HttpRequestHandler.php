<?php

namespace Waxwink\Orbis\Framework\Http;

use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Waxwink\Orbis\Contracts\ContainerInterface;

class HttpRequestHandler
{
    public function __construct(protected ContainerInterface $container, protected EventDispatcherInterface $eventDispatcher)
    {
    }

    public function handle(Request $request): Response
    {
        $response = new Response();
        $requestResponse = new RequestResponse($request, $response);
        $this->container->set(RequestResponse::class, $requestResponse);
        $this->eventDispatcher->dispatch($requestResponse);
        return $response;
    }
}
