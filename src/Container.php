<?php

namespace App;

use LogicException;
use RuntimeException;

class Container
{
    private array $parameters = [];
    private array $cache = [];

    /**
     * @throws \ReflectionException
     */
    public function build(string $className): object
    {
        if (!class_exists($className)) {
            throw new LogicException("Class '{$className}' not found.");
        }

        $this->cache[$className] ??= $this->instantiateClass($className);

        return $this->cache[$className];
    }

    public function setParameter(string $key, mixed $value): void
    {
        $this->parameters[$key] = $value;
    }

    /**
     * @throws \ReflectionException
     */
    private function instantiateClass(string $className): object
    {
        $reflection = new \ReflectionClass($className);

        if (!$reflection->isInstantiable()) {
            throw new RuntimeException("Class '{$className}' is not instantiable.");
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $reflection->newInstance();
        }

        return $this->instantiateWithDependencies($constructor);
    }

    /**
     * @throws \ReflectionException
     */
    private function instantiateWithDependencies(\ReflectionMethod $method): object
    {
        $dependencies = [];

        foreach ($method->getParameters() as $parameter) {
            $dependency = $this->resolveDependency($parameter);
            $dependencies[] = $dependency;
        }

        return $method->getDeclaringClass()->newInstanceArgs($dependencies);
    }

    /**
     * @throws \ReflectionException
     */
    private function resolveDependency(\ReflectionParameter $parameter): mixed
    {
        $parameterName = $parameter->getName();

        if ($parameter->getType() && !$parameter->getType()->isBuiltin()) {
            return $this->build($parameter->getType()->getName());
        }

        if (array_key_exists($parameterName, $this->parameters)) {
            return $this->parameters[$parameterName];
        }

        throw new RuntimeException("Unable to resolve dependency '{$parameterName}' for class.");
    }
}
