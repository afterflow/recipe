<?php

namespace Afterflow\Recipe\Recipes\Concerns;

trait HasDocBlock
{

    public function docBlock($value)
    {
        return $this->input('docBlock', $value);
    }
}
