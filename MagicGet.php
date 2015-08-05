<?php

trait MagicGet
{
    function __set(string $name, $value)
    {
        throw new Exception('Cannot set ' . $name . ' to ' . $value);
    }

    function __get(string $name)
    {
        $prop = '_' . $name;
        if (property_exists($this, $prop)) {
            return $this->{$prop};
        }

        throw new Exception('No such property: ' . $name);
    }
}
