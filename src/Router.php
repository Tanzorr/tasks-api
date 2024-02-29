<?php

namespace App;

class Router
{
    private $routes = [];
    private Container $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function get($url, $controllerMethod)
    {
        $this->addRoute('GET', $url, $controllerMethod);
    }

    public function post($url, $controllerMethod)
    {
        $this->addRoute('POST', $url, $controllerMethod);
    }

    public function delete($url, $controllerMethod)
    {
        $this->addRoute('DELETE', $url, $controllerMethod);
    }

    private function addRoute($method, $url, $controllerMethod)
    {
        // Replace "{param}" with a regular expression to capture a parameter
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
            // Replace "{param}" with a regular expression to capture a parameter
            $routeUrlPattern = preg_replace('/\{(\w+)\}/', '(?<$1>[^\/]+)', $routeUrl);

            // Check if the request URL matches the route pattern
            if (preg_match("#^$routeUrlPattern$#", $requestUrl, $matches)) {
                list($controller, $method) = explode('@', $controllerMethod);

                // Pass captured parameters to the controller method
                $this->callControllerMethod($controller, $method, $matches);

                return;
            }
        }

        // If no route matches, handle as not found
        $this->notFound();
    }


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

                    // Check if the parameter exists in the $params array
                    if (array_key_exists($paramName, $params)) {
                        $parameters[] = $params[$paramName];
                    } else {
                        // If not, use null as the argument
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


    private function notFound()
    {
        echo "not found";
    }
}