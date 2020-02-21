<?php

namespace Afterflow\Recipe\Recipes;

use Afterflow\Recipe\Recipe;

class ClassRecipe extends Recipe
{

    protected $template = __DIR__ . '/../../templates/class.blade.php';

    protected $props = [
        'name'    => [
            'rules' => 'required',
        ],
        'imports' => [
            'default' => [],
        ],
    ];

    public function namespace($value)
    {
        $this->data[ 'namespace' ] = $value;

        return $this;
    }

    public function name($value)
    {
        $this->data[ 'name' ] = $value;

        return $this;
    }

    public function traits($value)
    {
        $this->data[ 'traits' ] = $value;

        return $this;
    }

    public function imports($value)
    {
        $this->data[ 'imports' ] = $value;

        return $this;
    }

    public function implements($value)
    {
        $this->data[ 'implements' ] = $value;

        return $this;
    }

    public function extends($value)
    {
        $this->data[ 'extends' ] = $value;

        return $this;
    }
}
