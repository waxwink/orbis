<?php

namespace Waxwink\Orbis\Application;

use Waxwink\Orbis\Container\Container;
use Waxwink\Orbis\Contracts\Bootable;
use Waxwink\Orbis\Contracts\ContainerInterface;

class Application
{
    protected ContainerInterface $container;

    protected KernelManager $kernelManager;

    protected string $env = 'dev';

    public function __construct(...$arguments)
    {
        if (array_key_exists('env', $arguments)) {
            $this->env = (string)$arguments['env'];
        }

        $this->container = array_key_exists('container', $arguments) ?
            $arguments['container'] : $this->makeContainer();

        $this->kernelManager = array_key_exists('kernelManager', $arguments) ?
            $arguments['kernelManager'] : $this->makeKernelManager($arguments);

        $this->registerApp($arguments);
        $this->registerContainer();
        $this->registerKernelManager();
    }

    public function run(string $mode): void
    {
        try {
            $this->loadProviders($this->kernelManager->resolveProviders($mode, $this->env));
            $this->bootBootable($this->container->get(
                $this->kernelManager->getKernel($mode)
            ));
        } catch (\Throwable $throwable) {
            try {
                $this->container->get(
                    $this->kernelManager->resolveExceptionHandler($mode)
                )->handle($throwable);
            } catch (\Throwable $e) {
                echo $throwable->getMessage();
            }
        }
    }

    protected function makeContainer(): ContainerInterface
    {
        return new Container();
    }

    protected function makeKernelManager($arguments): KernelManager
    {
        return new KernelManager($arguments);
    }

    protected function bootBootable(Bootable $loadable): void
    {
        $loadable->boot();
    }

    protected function registerApp($arguments): void
    {
        $this->container->set("app", $this);
        $this->container->set('config', $arguments);
    }

    private function registerContainer(): void
    {
        $this->container->set(get_class($this->container), $this->container);
        $this->container->set(\Psr\Container\ContainerInterface::class, $this->container);
        $this->container->set(ContainerInterface::class, $this->container);
    }

    protected function registerKernelManager(): void
    {
        $this->container->set("kernel.manager", $this->kernelManager);
    }

    protected function loadProviders(array $providers): void
    {
        foreach ($providers as $provider) {
            $this->bootBootable($this->container->get($provider));
        }
    }
}
