<?php

namespace Afterflow\Recipe\Recipes\Concerns;

trait MagicSetters
{

    public function __call($name, $arguments)
    {

        if ($arguments === []) {
            //          return $this->input( $name );
            return $this->data($name);
        }

        return $this->input($name, ...$arguments);
    }
}
