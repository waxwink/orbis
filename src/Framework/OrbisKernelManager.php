<?php

namespace Waxwink\Orbis\Framework;

use Waxwink\Orbis\Application\KernelManager;
use Waxwink\Orbis\CommonCommands\CommonCommandsProvider;
use Waxwink\Orbis\Configuration\ConfigurationProvider;
use Waxwink\Orbis\Console\CommandContainerProvider;
use Waxwink\Orbis\ControllerCaller\ControllerCallerProvider;
use Waxwink\Orbis\EventDispatcher\EventDispatcherProvider;
use Waxwink\Orbis\Framework\Console\ConsoleKernel;
use Waxwink\Orbis\Framework\Console\ExceptionHandler as ConsoleExceptionHandlerAlias;
use Waxwink\Orbis\Framework\Http\ExceptionHandler as HttpExceptionHandler;
use Waxwink\Orbis\Framework\Http\HttpKernel;
use Waxwink\Orbis\Logger\LoggerProvider;
use Waxwink\Orbis\Router\RouterProvider;

class OrbisKernelManager extends KernelManager
{
    protected $kernels = [
        'http' => HttpKernel::class,
        'console' => ConsoleKernel::class,
    ];

    /**
     * These providers should not be overwritten
     */
    protected $baseProviders = [
        ConfigurationProvider::class,
        EventDispatcherProvider::class,
        LoggerProvider::class,
        RouterProvider::class,
        ControllerCallerProvider::class
    ];

    protected $exceptionHandlers = [
        'http' => HttpExceptionHandler::class,
        'console' => ConsoleExceptionHandlerAlias::class
    ];

    protected $modeProviders = [
        'console' => [
            CommandContainerProvider::class,
            CommonCommandsProvider::class
        ]
    ];
}
