<?php

namespace App;

class App
{

    public Container $container;
    public Router $router;
    public function __construct()
    {
        $this->container = new Container();
        $this->router = new Router($this->container);
    }

    public function setPatch(string $key, string $value ): void
    {
        $this->container->setParameter($key, $value);
    }
}