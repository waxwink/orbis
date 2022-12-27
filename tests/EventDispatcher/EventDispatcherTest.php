<?php

namespace Waxwink\Orbis\Tests\EventDispatcher;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Psr\Container\ContainerInterface;
use Waxwink\Orbis\EventDispatcher\EventDispatcher;

class EventDispatcherTest extends MockeryTestCase
{
    private \Mockery\LegacyMockInterface|MockInterface|ContainerInterface $container;
    private EventDispatcher $dispatcher;

    protected function setUp(): void
    {
        $this->container = \Mockery::mock(ContainerInterface::class);
        $this->dispatcher = new EventDispatcher($this->container);
    }

    public function testListenersCanBeAddedToAnEvent(): void
    {
        $listener = "listener_class_name";
        $event = "event_name";

        $this->dispatcher->addListener($event, $listener);

        $this->assertEquals([$event =>[$listener]], $this->dispatcher->getListeners());
    }

    public function testCanDispatchARegisteredEvent(): void
    {
        $listener =new MockListener();
        $event = new MockEvent();

        $this->dispatcher->addListener(MockEvent::class, MockListener::class);

        $this->container->shouldReceive("get")->with(MockListener::class)->once()->andReturn($listener);

        $this->dispatcher->dispatch($event);
    }
}

class MockEvent
{
    //
}

class MockListener
{
    public function __invoke(MockEvent $mockEvent)
    {
        // TODO: Implement __invoke() method.
    }
}
