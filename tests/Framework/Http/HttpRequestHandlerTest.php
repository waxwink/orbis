<?php

namespace Waxwink\Orbis\Tests\Framework\Http;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Waxwink\Orbis\Contracts\ContainerInterface;
use Waxwink\Orbis\Framework\Http\HttpRequestHandler;

class HttpRequestHandlerTest extends MockeryTestCase
{
    protected \PHPUnit\Framework\MockObject\MockObject|\Psr\Container\ContainerInterface $container;
    protected EventDispatcherInterface|\PHPUnit\Framework\MockObject\MockObject $eventDispatcher;

    protected function setUp(): void
    {
        $this->container = \Mockery::mock(ContainerInterface::class);
        $this->eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
    }

    public function testHandlesRequestAndReturnsResponse(): void
    {
        $httpRequestHandler = new HttpRequestHandler($this->container, $this->eventDispatcher);

        $this->eventDispatcher->shouldReceive("dispatch")->once();
        $this->container->shouldReceive("set")->once();
        $httpRequestHandler->handle(new Request());
    }
}
