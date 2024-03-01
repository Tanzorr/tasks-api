<?php

namespace App;

class App
{
    private Container $container;
    public Router $router;
    public function __construct()
    {
        $this->container = new Container();
        $this->router = new Router($this->container);
    }

    public function setPath(string $key, string $value ): void
    {
        $this->container->setParameter($key, $value);
    }
}