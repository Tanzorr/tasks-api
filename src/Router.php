<?php

namespace App;

class Router
{
    private array $routes = [];
    public function __construct(private Container $container)
    {
    }

    public function get($url, $controllerMethod): void
    {
        $this->addRoute('GET', $url, $controllerMethod);
    }

    public function post($url, $controllerMethod): void
    {
        $this->addRoute('POST', $url, $controllerMethod);
    }

    public function delete($url, $controllerMethod): void
    {
        $this->addRoute('DELETE', $url, $controllerMethod);
    }

    private function addRoute($method, $url, $controllerMethod): void
    {
        $url = preg_replace('/\{(\w+)\}/', '(?<$1>[^\/]+)', $url);

        $this->routes[$method][$url] = $controllerMethod;
    }

    /**
     * @throws \ReflectionException
     */
    public function route(): void
    {
        $requestUrl = $_SERVER['REQUEST_URI'];
        $requestUrl = strtok($requestUrl, '?');
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$requestMethod] as $routeUrl => $controllerMethod) {
            $routeUrlPattern = preg_replace('/\{(\w+)\}/', '(?<$1>[^\/]+)', $routeUrl);

            if (preg_match("#^$routeUrlPattern$#", $requestUrl, $matches)) {
                list($controller, $method) = explode('@', $controllerMethod);

                $this->callControllerMethod($controller, $method, $matches);

                return;
            }
        }

        $this->notFound();
    }


    /**
     * @throws \ReflectionException
     */
    private function callControllerMethod($controller, $method, $params): void
    {
        $controllerClassName = 'App\\' . $controller;

        if (class_exists($controllerClassName)) {
            $controllerInstance = $this->container->build($controllerClassName);

            if (method_exists($controllerInstance, $method)) {
                $reflectionMethod = new \ReflectionMethod($controllerInstance, $method);
                $parameters = [];

                foreach ($reflectionMethod->getParameters() as $param) {
                    $paramName = $param->getName();

                    if (array_key_exists($paramName, $params)) {
                        $parameters[] = $params[$paramName];
                    } else {
                        $parameters[] = null;
                    }
                }

                $reflectionMethod->invokeArgs($controllerInstance, $parameters);
            } else {
                $this->notFound();
            }
        } else {
            $this->notFound();
        }
    }


    private function notFound(): void
    {
        echo "not found";
    }
}