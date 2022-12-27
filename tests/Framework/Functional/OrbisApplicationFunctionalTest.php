<?php

namespace Waxwink\Orbis\Tests\Framework\Functional;

use Waxwink\Orbis\Contracts\Bootable;
use Waxwink\Orbis\EventDispatcher\EventDispatcherInterface;
use Waxwink\Orbis\Framework\Http\RequestResponse;

class OrbisApplicationFunctionalTest extends \PHPUnit\Framework\TestCase
{
    public function testCanBeRun(): void
    {
        $this->expectOutputString("hello world");
        $app = new \Waxwink\Orbis\Framework\OrbisApplication(env: 'test', modeProviders: ['http' => [AppProvider::class]]);
        $app->run('http');
    }
}

class AppProvider implements Bootable
{
    public function __construct(protected EventDispatcherInterface $eventDispatcher)
    {
    }

    public function boot(): void
    {
        $this->eventDispatcher->addListener(RequestResponse::class, MainListener::class);
    }
}

class MainListener
{
    public function __invoke(RequestResponse $requestResponse)
    {
        $requestResponse->getResponse()->setContent("hello world");
    }
}
