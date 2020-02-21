<?php

namespace Afterflow\Recipe\Recipes\Concerns;

trait HasDocBlock
{

    public function docBlock($value)
    {
        $this->data[ 'docBlock' ] = $value;

        return $this;
    }
}
