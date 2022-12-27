<?php

namespace Waxwink\Orbis\Tests\Application;

use Exception;
use PHPUnit\Framework\TestCase;
use Throwable;
use Waxwink\Orbis\Application\Application;

class ApplicationTest extends TestCase
{
    public function testApplicationCanRunAnyBootableClass(): void
    {
        $this->expectOutputString("hello from the kernel.");
        $app = new Application(
            kernels: ["mock" => MockBootable::class],
            exceptionHandlers: ["mock" => MockExceptionHandler::class]
        );

        $app->run('mock');
    }

    public function testExternalProvidersCanBeBoundToTheApplication(): void
    {
        $this->expectOutputString("hello from the mock provider. hello from the kernel.");
        $app = new Application(
            kernels: ["mock" => MockBootable::class],
            exceptionHandlers: ["mock" => MockExceptionHandler::class],
            defaultProviders: [MockProvider::class]
        );
        $app->run('mock');
    }

    public function testIfTheInjectedProviderThrowsExceptionTheExceptionHandlerWouldCatchIt(): void
    {
        $this->expectOutputString("exception from the faulty provider");
        $app = new Application(
            kernels: ["mock" => MockBootable::class],
            exceptionHandlers: ["mock" => MockExceptionHandler::class],
            defaultProviders: [FaultyMockProvider::class]
        );
        $app->run('mock');
    }

    public function testIfInputModeIsNotRegisteredThenModeNotFoundExceptionWouldBeThrown(): void
    {
        $this->expectException(\Waxwink\Orbis\Application\ModeNotFoundException::class);
        $app = new Application(
            exceptionHandlers: ["mock" => MockExceptionNotHandler::class],
        );
        $app->run('mock');
    }

    public function testIfTheEnvironmentIsNotDevThenTheDevProvidersWouldNotBeLoaded(): void
    {
        $this->expectOutputString("hello from the dev mock provider. hello from the kernel.");
        $app = new Application(
            kernels: ["mock" => MockBootable::class],
            exceptionHandlers: ["mock" => MockExceptionHandler::class],
            devProviders: [DevMockProvider::class]
        );
        $app->run('mock');

        ob_clean();

        $this->expectOutputString("hello from the dev mock provider. hello from the kernel.");
        $app = new Application(
            env: 'dev',
            kernels: ["mock" => MockBootable::class],
            exceptionHandlers: ["mock" => MockExceptionHandler::class],
            devProviders: [DevMockProvider::class]
        );
        $app->run('mock');

        ob_clean();

        $this->expectOutputString("hello from the kernel.");
        $app = new Application(
            env: 'local',
            kernels: ["mock" => MockBootable::class],
            exceptionHandlers: ["mock" => MockExceptionHandler::class],
            devProviders: [DevMockProvider::class]
        );
        $app->run('mock');
    }
}

class MockBootable implements \Waxwink\Orbis\Contracts\Bootable
{
    public function boot(): void
    {
        echo "hello from the kernel.";
    }
}

class MockExceptionHandler implements \Waxwink\Orbis\Contracts\ExceptionHandlerInterface
{
    public function handle(Throwable $exception): void
    {
        echo $exception->getMessage();
    }
}

class MockExceptionNotHandler implements \Waxwink\Orbis\Contracts\ExceptionHandlerInterface
{
    public function handle(Throwable $exception): void
    {
        throw $exception;
    }
}

class MockProvider implements \Waxwink\Orbis\Contracts\Bootable
{
    public function boot(): void
    {
        echo "hello from the mock provider. ";
    }
}

class FaultyMockProvider implements \Waxwink\Orbis\Contracts\Bootable
{
    /**
     * @throws Exception
     */
    public function boot(): void
    {
        throw new Exception("exception from the faulty provider");
    }
}

class DevMockProvider implements \Waxwink\Orbis\Contracts\Bootable
{
    /**
     * @throws Exception
     */
    public function boot(): void
    {
        echo "hello from the dev mock provider. ";
    }
}
