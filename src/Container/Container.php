<?php

namespace Waxwink\Orbis\Container;

use http\Exception\RuntimeException;
use Waxwink\Orbis\Contracts\ContainerInterface;

class Container implements ContainerInterface
{
    protected $registered = [];

    public function get(string $id)
    {
        if (array_key_exists($id, $this->registered)) {
            if (is_callable($this->registered[$id])) {
                return $this->resolveFromCallable($this->registered[$id], $id);
            }

            if (!is_string($this->registered[$id])) {
                return $this->registered[$id];
            }

            return $this->get($this->registered[$id]);
        }

        try {
            return $this->resolve($id);
        } catch (\Throwable $e) {
            throw new EntityNotFound("Unable to resolve the id of : $id. " . $e->getMessage());
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


    /**
     * @throws \ReflectionException
     */
    private function resolve(string $id)
    {
        $this->set($id, $entity = new $id(...$this->inputParameters(
            (new \ReflectionClass($id))->getConstructor()?->getParameters()
        )));

        return $entity;
    }


    private function resolveParameter(\ReflectionParameter $parameter)
    {
        if ($this->has($parameter->getName())) {
            return $this->get($parameter->getName());
        }
        $class = $parameter->getType()?->getName();
        if ($class && !in_array($class, ["bool", "string", "array", "mixed", "int", "object", "callable"])) {
            return $this->get($class);
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw new \Exception("Unable to resolve the parameter : " . $parameter->getName());
    }

    public function loadServices(array $services)
    {
        $this->registered = $services;
    }

    public function call(string $class, string $method): mixed
    {
        $parameters = (new \ReflectionClass($class))->getMethod($method)?->getParameters();
        $entity = $this->get($class);

        if (!method_exists($entity, $method)) {
            throw new \RuntimeException(sprintf('Class %s does not have method %s.', $class, $method));
        }

        return $entity->{$method}(...$this->inputParameters($parameters));
    }


    private function inputParameters(?array $parameters): array
    {
        if (!$parameters) {
            return [];
        }
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

    /**
     * @throws \ReflectionException
     */
    private function resolveFromCallable(callable $callable, $id)
    {
        $this->set($id, $entity = $callable(...$this->inputParameters((new \ReflectionFunction($callable))->getParameters())));
        return $entity;
    }
}
