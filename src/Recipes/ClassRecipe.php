<?php

namespace Afterflow\Recipe\Recipes;

use Afterflow\Recipe\Recipe;

/**
 * Class ClassRecipe
 * @package Afterflow\Recipe\Recipes
 */
class ClassRecipe extends Recipe
{

    /**
     * @var string
     */
    protected $template = __DIR__ . '/../../templates/class.blade.php';

    /**
     * @var array
     */
    protected $props = [
        'name'    => [
            'rules' => 'required',
        ],
        'imports' => [
            'default' => [],
        ],
    ];

    /**
     * @param $value
     *
     * @return $this
     */
    public function namespace($value)
    {
        $this->data[ 'namespace' ] = $value;

        return $this;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function content($value)
    {
        $this->data[ 'content' ] = $value;

        return $this;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function name($value)
    {
        $this->data[ 'name' ] = $value;

        return $this;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function traits($value)
    {
        $this->data[ 'traits' ] = $value;

        return $this;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function imports($value)
    {
        $this->data[ 'imports' ] = $value;

        return $this;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function implements($value)
    {
        $this->data[ 'implements' ] = $value;

        return $this;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function extends($value)
    {
        $this->data[ 'extends' ] = $value;

        return $this;
    }
}
