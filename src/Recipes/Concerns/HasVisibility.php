<?php

namespace Afterflow\Recipe\Recipes\Concerns;

/**
 * Trait HasVisibility
 * @package Afterflow\Recipe\Recipes\Concerns
 */
trait HasVisibility
{

    /**
     * @param $value
     *
     * @return static
     */
    public function visibility($value)
    {
        return $this->input('visibility', $value);
    }

    /**
     * @return static
     */
    public function protected()
    {

        return $this->visibility('protected');
    }

    /**
     * @return static
     */
    public function private()
    {

        return $this->visibility('private');
    }

    /**
     * @return static
     */
    public function public()
    {

        return $this->visibility('public');
    }
}
