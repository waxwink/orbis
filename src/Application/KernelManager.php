<?php

namespace Waxwink\Orbis\Application;

use Waxwink\Orbis\Contracts\Bootable;

class KernelManager
{
    protected $kernels = [
        //
    ];

    protected $baseProviders = [
        //
    ];

    protected $defaultProviders = [
        //
    ];

    protected $modeProviders = [
        //
    ];

    protected $devProviders = [
        //
    ];

    protected $exceptionHandlers = [
        //
    ];

    public function __construct(array $arguments)
    {
        foreach ($arguments as $key => $value) {
            if (property_exists($this, $key)) {
                $this->appendToProperty($key, $value);
            }
        }
    }


    /**
     * @return Bootable[]
     */
    public function resolveProviders(string $mode, ?string $env): array
    {
        return array_unique(array_merge(
            $this->baseProviders,
            $this->defaultProviders,
            array_key_exists($mode, $this->modeProviders) ? $this->modeProviders[$mode] : [],
            $env === 'dev' ? $this->devProviders : []
        ));
    }

    public function getKernel(string $mode): string
    {
        if (! array_key_exists($mode, $this->kernels)) {
            throw new ModeNotFoundException($mode . ' mode is not registered in the Kernel manager class');
        }
        return $this->kernels[$mode];
    }

    public function resolveExceptionHandler(string $mode): string
    {
        return $this->exceptionHandlers[$mode];
    }

    public function addKernels($mode, $kernelClass): KernelManager
    {
        $this->kernels[$mode] = $kernelClass;

        return $this;
    }

    public function addExceptionHandler($mode, $handlerClass): KernelManager
    {
        $this->exceptionHandlers[$mode] = $handlerClass;

        return $this;
    }

    public function addDefaultProvider(string $provider): KernelManager
    {
        $this->defaultProviders[] = $provider;

        return $this;
    }

    public function addDevProvider(string $provider): KernelManager
    {
        $this->devProviders[] = $provider;

        return $this;
    }

    protected function appendToProperty(string $key, array $value): void
    {
        $this->$key = array_merge_recursive($this->$key, $value);
    }

    protected function setBaseProviders(array $baseProviders): KernelManager
    {
        $this->baseProviders = $baseProviders;
        return $this;
    }
}
