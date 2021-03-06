<?php

namespace App\Models;

abstract class Base
{
    public function __construct(array $options = null)
    {
        if (is_array($options))
            $this->setOptions($options);
    }

    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (!method_exists($this, $method))
            throw new Exception('Invalid property ' . __CLASS__ . '->' . $name );
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if (!method_exists($this, $method))
            throw new Exception('Invalid property ' . __CLASS__ . '->' . $name );

        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods))
                $this->$method($value);
        }

        return $this;
    }
}
