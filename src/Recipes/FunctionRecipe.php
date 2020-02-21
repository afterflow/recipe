<?php

namespace Afterflow\Recipe\Recipes;

use Afterflow\Recipe\Recipe;

class FunctionRecipe extends Recipe
{

    protected $template = __DIR__ . '/../../templates/function.blade.php';

    protected $props = [
        'name'       => [
            'rules' => 'required',
        ],
        'arguments'  => [],
        'visibility' => null,
        'body'       => '',
        'static'     => false,
        'abstract'   => false,
        'docBlock'   => '',
        'return'     => [],
    ];

    public function dataForTemplate()
    {

        $data = $this->data;

        $data['arguments'] = collect($data['arguments'])->implode(', ');

        return $data;
    }
}
