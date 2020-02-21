<?php

namespace Afterflow\Recipe\Recipes\Concerns;

trait HasVisibility
{

    public function visibility($value)
    {
        $this->data[ 'visibility' ] = $value;

        return $this;
    }

    public function protected()
    {

        return $this->visibility('protected');
    }

    public function private()
    {

        return $this->visibility('private');
    }

    public function public()
    {

        return $this->visibility('public');
    }
}
