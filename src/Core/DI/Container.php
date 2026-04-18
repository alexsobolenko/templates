<?php

declare(strict_types=1);

namespace App\Core\DI;

use App\Core\App;
use App\Exception\Container\ContainerException;
use App\Exception\Container\NotFoundException;
use Psr\Container\ContainerInterface;

final class Container implements ContainerInterface
{
    /**
     * @var array
     */
    private array $entries = [];

    /**
     * @var array
     */
    private array $resolved = [];

    /**
     * @param string $id
     * @param mixed $definition
     */
    public function set(string $id, mixed $definition): void
    {
        $this->entries[$id] = $definition;
    }

    /**
     * @param string $id
     * @return bool
     */
    #[\Override]
    public function has(string $id): bool
    {
        return isset($this->entries[$id])
            || isset($this->resolved[$id])
            || class_exists($id);
    }

    /**
     * @param string $id
     * @return mixed
     * @throws NotFoundException
     */
    #[\Override]
    public function get(string $id): mixed
    {
        if (!isset($this->entries[$id])) {
            throw new NotFoundException('Entry "' . $id . '" is not registered.');
        }

        return $this->entries[$id];
    }

    /**
     * @param string $id
     * @return mixed
     * @throws ContainerException
     */
    public function resolve(string $id): mixed
    {
        if (!isset($this->resolved[$id])) {
            $arguments = [];
            $serviceConfig = App::serviceConfig($id, []);
            if (is_array($serviceConfig)) {
                $arguments = $serviceConfig['arguments'] ?? [];
                if (!is_array($arguments)) {
                    throw new ContainerException('Service arguments definition must be an array.');
                }
            }

            $reflectionClass = new \ReflectionClass($id);
            if (!$reflectionClass->isInstantiable()) {
                throw new ContainerException('Class "' . $id . '" is not instantiable');
            }

            $constructor = $reflectionClass->getConstructor();
            $dependencies = array_map(function (\ReflectionParameter $reflectionParameter) use ($id, $arguments) {
                $name = $reflectionParameter->getName();
                if (key_exists($name, $arguments)) {
                    return $arguments[$name];
                }

                $type = $reflectionParameter->getType();
                if (!$type) {
                    throw new ContainerException('Failed to resolve class "' . $id . '" because param "' . $name . '" is missing a type hint');
                }
                if ($type instanceof \ReflectionUnionType) {
                    throw new ContainerException('Failed to resolve class "' . $id . '" because of union type for param "' . $name . '"');
                }

                if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                    return $this->resolve($type->getName());
                }

                if ($reflectionParameter->isDefaultValueAvailable()) {
                    return $reflectionParameter->getDefaultValue();
                }

                throw new ContainerException('Failed to resolve class "' . $id . '" because invalid param "' . $name . '"');
            }, $constructor instanceof \ReflectionMethod ? $constructor->getParameters() : []);

            $this->resolved[$id] = empty($dependencies) ? (new $id()) : $reflectionClass->newInstanceArgs($dependencies);
        }

        return $this->resolved[$id];
    }
}
