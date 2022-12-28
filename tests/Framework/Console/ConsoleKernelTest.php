<?php

namespace Waxwink\Orbis\Tests\Framework\Console;

use Mockery;
use Waxwink\Orbis\Console\CommandContainer;

class ConsoleKernelTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    private Mockery\LegacyMockInterface|Mockery\MockInterface|\Psr\Container\ContainerInterface $container;
    private Mockery\LegacyMockInterface|Mockery\MockInterface|\Waxwink\Orbis\Configuration\ConfigurationInterface $config;
    private \Waxwink\Orbis\Framework\Console\ConsoleKernel $console;

    protected function setUp(): void
    {
        $this->container = Mockery::mock(\Psr\Container\ContainerInterface::class);
        $this->config = Mockery::mock(\Waxwink\Orbis\Configuration\ConfigurationInterface::class);

        $this->console = new \Waxwink\Orbis\Framework\Console\ConsoleKernel($this->container, $this->config, new CommandContainer());
    }

    public function testOneCanRegisterACommandForApplicationInstantiation(): void
    {
        $this->registerSingleCommand(MockCommand::class);

        $this->assertEquals(["mock:command" => MockCommand::class], $this->console->getRegistered());
    }

    public function testUserCanResolveTheCommandAfterRegisteringIt(): void
    {
        $this->registerSingleCommand(MockCommand::class);

        $this->container->shouldReceive("get")->with(MockCommand::class)->andReturn(new MockCommand());

        $this->assertInstanceOf(MockCommand::class, $this->console->resolveCommand("mock:command"));
    }

    public function testUserCanRunACommandThroughTheConsoleKernel(): void
    {
        $this->registerSingleCommand(MockCommand::class);

        $this->container->shouldReceive("get")->with(MockCommand::class)->andReturn(new MockCommand());

        $this->expectOutputString("hello world");

        $this->console->runCommand(["mock:command"]);
    }

    public function testUserCanRunACommandThroughTheConsoleKernelWithInputs(): void
    {
        $this->registerSingleCommand(MockCommandWithInputs::class);

        $this->container->shouldReceive("get")->with(MockCommandWithInputs::class)->andReturn(new MockCommandWithInputs());

        $this->expectOutputString("Good morning John");

        $this->console->runCommand(["mock:greeting", "John", "--time=morning"]);

        ob_clean();

        $this->expectOutputString("Good afternoon John");

        $this->console->runCommand(["mock:greeting", "John", "--time=afternoon"]);

        ob_clean();

        $this->expectOutputString("Hi John");

        $this->console->runCommand(["mock:greeting", "John"]);
    }

    /**
     * @param string $commandClassName
     * @return void
     */
    private function registerSingleCommand(string $commandClassName): void
    {
        $this->config->shouldReceive("get")->with("console.commands")->andReturn(
            [$commandClassName]
        );

        $this->console->loadCommands();
    }
}

class MockCommand extends \Waxwink\Orbis\Console\Command
{
    public const NAME = "mock:command";

    public function __invoke()
    {
        echo "hello world";
    }
}

class MockCommandWithInputs extends \Waxwink\Orbis\Console\Command
{
    public const NAME = "mock:greeting";

    public function __invoke($name)
    {
        if ($this->time ==="morning") {
            $greeting = "Good morning";
        } elseif ($this->time ==="afternoon") {
            $greeting = "Good afternoon";
        } else {
            $greeting = "Hi";
        }

        $greeting .= " $name";

        echo $greeting;
    }
}
