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
}
