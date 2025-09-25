<?php
class Container {
    private $bindings = [];
    private $instances = [];

    public function bind($abstract, $concrete = null) {
        if ($concrete === null) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = $concrete;
    }

    public function singleton($abstract, $concrete = null) {
        $this->bind($abstract, $concrete);
        $this->instances[$abstract] = null;
    }

    public function instance($abstract, $instance) {
        $this->instances[$abstract] = $instance;
    }

    public function make($abstract) {
        // Check if singleton instance exists
        if (array_key_exists($abstract, $this->instances) && $this->instances[$abstract] !== null) {
            return $this->instances[$abstract];
        }

        // Get concrete implementation
        $concrete = $this->bindings[$abstract] ?? $abstract;

        // Build the instance
        $instance = $this->build($concrete);

        // Store singleton instance
        if (isset($this->instances[$abstract])) {
            $this->instances[$abstract] = $instance;
        }

        return $instance;
    }

    private function build($concrete) {
        // If it's a closure, call it
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        // If it's a string, build class
        if (is_string($concrete)) {
            return $this->buildClass($concrete);
        }

        return $concrete;
    }

    private function buildClass($className) {
        $reflection = new ReflectionClass($className);

        if (!$reflection->isInstantiable()) {
            throw new Exception("Class {$className} is not instantiable");
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $className;
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type === null) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new Exception("Cannot resolve parameter {$parameter->getName()} in {$className}");
                }
            } else {
                $typeName = method_exists($type, 'getName') ? $type->getName() : (string) $type;

                if ($typeName === 'PDO' || $typeName === 'mysqli') {
                    // Special handling for database connections
                    global $db;
                    $dependencies[] = $db;
                } else {
                    $dependencies[] = $this->make($typeName);
                }
            }
        }

        return $reflection->newInstanceArgs($dependencies);
    }

    public function call($callback, $parameters = []) {
        if (is_array($callback)) {
            [$class, $method] = $callback;
            $instance = is_object($class) ? $class : $this->make($class);
            $callback = [$instance, $method];
        }

        return call_user_func_array($callback, $parameters);
    }
}
?>