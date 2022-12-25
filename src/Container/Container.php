<?php

namespace Waxwink\Orbis\Container;

use Waxwink\Orbis\Contracts\ContainerInterface;

class Container implements ContainerInterface
{
    protected $registered = [];

    public function get(string $id)
    {
        if (array_key_exists($id, $this->registered)) {
            if (!is_string($this->registered[$id])) {
                return $this->registered[$id];
            }

            return $this->get($this->registered[$id]);
        }

        try {
            return $this->resolve($id);
        } catch (ServiceNotFound $e) {
            throw new ServiceNotFound("Unable to resolve the id of : $id. " . $e->getMessage());
        } catch (\Exception|\Error $e) {
            throw new ServiceNotFound("Unable to resolve the id of : $id. " . $e->getMessage());
        }
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->registered);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->registered;
    }


    private function resolve(string $id)
    {
        $refClass = new \ReflectionClass($id);
        $parameters = $refClass->getConstructor()?->getParameters();

        if (!$parameters) {
            $this->set($id, new $id());
        } else {
            $this->set($id, new $id(...$this->inputParameters($parameters)));
        }


        return $this->registered[$id];
    }


    private function resolveParameter(\ReflectionParameter $parameter)
    {
        if ($this->has($parameter->getName())) {
            return $this->get($parameter->getName());
        }
        $class = $parameter->getType()?->getName();
        if (!$class || in_array($class, ["bool", "string", "array", "mixed"])) {
            throw new \Exception("Unable to resolve the parameter : " . $parameter->getName());
        }

        return $this->get($class);
    }

    public function loadServices(array $services)
    {
        $this->registered = $services;
    }

    public function call(string $controller, string $method): mixed
    {
        $refClass = new \ReflectionClass($controller);
        $parameters = $refClass->getMethod($method)?->getParameters();
        $controller = $this->get($controller);

        return $controller->$method(...$this->inputParameters($parameters));
    }


    private function inputParameters(array $parameters): array
    {
        $inputParameters = [];

        foreach ($parameters as $parameter) {
            $inputParameters[] = $this->resolveParameter($parameter);
        }
        return $inputParameters;
    }

    public function set(string $id, $value): void
    {
        $this->registered[$id] = $value;
    }
}
