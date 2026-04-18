<?php

declare(strict_types=1);

namespace App\Core\Http;

use App\Attribute\Route;
use App\Core\App;
use App\Exception\Http\HttpNotFoundException;
use App\Exception\Http\InvalidControllerResponseException;
use App\Exception\Http\MethodNotAllowedException;

final class Router
{
    /**
     * @var array
     */
    private array $routes = [];

    /**
     * @param string $directory
     * @param string $namespace
     */
    public function registerControllers(string $directory, string $namespace): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory));
        foreach ($iterator as $file) {
            if (!$file instanceof \SplFileInfo || $file->getExtension() !== 'php') {
                continue;
            }

            $relativePath = substr($file->getPathname(), strlen($directory) + 1, -4);
            $className = $namespace . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);
            if (class_exists($className)) {
                $this->registerController($className);
            }
        }
    }

    /**
     * @param string $controllerClass
     */
    public function registerController(string $controllerClass): void
    {
        $reflection = new \ReflectionClass($controllerClass);
        foreach ($reflection->getMethods() as $method) {
            foreach ($method->getAttributes(Route::class) as $attribute) {
                /** @var Route $route */
                $route = $attribute->newInstance();
                $this->routes[] = [
                    'path' => $route->path,
                    'class' => $controllerClass,
                    'action' => $method->getName(),
                    'methods' => array_map('strtoupper', $route->methods),
                ];
            }
        }
    }

    /**
     * @return Response
     * @throws HttpNotFoundException
     * @throws InvalidControllerResponseException
     */
    public function dispatch(): Response
    {
        $foundRoutes = [];
        $routeParams = [];
        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $route['path']);
            $matches = [];
            if (!is_string($pattern) || !preg_match('#^' . $pattern . '$#', App::$request->path, $matches)) {
                continue;
            }

            $foundRoutes[] = $route;
            if (!empty($routeParams)) {
                continue;
            }

            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $routeParams[$key] = $value;
                }
            }
            break;
        }

        if (empty($foundRoutes)) {
            throw new HttpNotFoundException(
                'Route not found for "' . App::$request->method . ' ' . App::$request->path .  '".'
            );
        }

        $handler = null;
        foreach ($foundRoutes as $route) {
            if (in_array(App::$request->method, $route['methods'], true)) {
                $handler = $route;
                break;
            }
        }

        if ($handler === null) {
            throw new MethodNotAllowedException(
                'Method not allowed ' . App::$request->method . ' for " ' . App::$request->path .  '".'
            );
        }

        $controller = App::$container->resolve($handler['class']);
        $reflectionMethod = new \ReflectionMethod($controller, $handler['action']);
        $arguments = array_map(function (\ReflectionParameter $reflectionParameter) use ($handler, $routeParams) {
            $name = $reflectionParameter->getName();
            if (key_exists($name, $routeParams)) {
                return $routeParams[$name];
            }

            $type = $reflectionParameter->getType();
            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                return App::$container->resolve($type->getName());
            }

            if ($reflectionParameter->isDefaultValueAvailable()) {
                return $reflectionParameter->getDefaultValue();
            }

            throw new InvalidControllerResponseException(
                'Failed to resolve action argument "' . $name . '" for ' . $handler['class'] . ':: ' . $handler['action'] . '.'
            );
        }, $reflectionMethod->getParameters());
        $response = call_user_func_array([$controller, $handler['action']], $arguments);
        if (!$response instanceof Response) {
            throw new InvalidControllerResponseException('Controller action must return a Response instance.');
        }

        return $response;
    }
}
