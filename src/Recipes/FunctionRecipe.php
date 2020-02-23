<?php

namespace Afterflow\Recipe\Recipes;

use Afterflow\Recipe\Recipe;
use Afterflow\Recipe\Recipes\Concerns\HasDocBlock;
use Afterflow\Recipe\Recipes\Concerns\HasVisibility;

class FunctionRecipe extends Recipe
{
    use HasDocBlock;
    use HasVisibility;

    protected $template = __DIR__ . '/../../templates/function.blade.php';

    protected $props = [
        'name'       => [
            'rules' => 'required',
        ],
        'arguments'  => [
            'default' => [],
            'rules'   => 'array',
        ],
        'visibility' => null,
        'body'       => '',
        'static'     => [
            'default' => false,
            'rules'   => 'boolean',
        ],
        'abstract'   => [
            'default' => false,
            'rules'   => 'boolean',
        ],
        'docBlock'   => '',
        'return'     => [],
    ];

    public function arguments($value)
    {
        return $this->input('arguments', $value);
    }

    public function body($value)
    {
        return $this->input('body', $value);
    }

    public function name($value)
    {
        return $this->input('name', $value);
    }

    public function return($value)
    {
        return $this->input('return', $value);
    }

    public function abstract()
    {
        return $this->input('abstract', true);
    }

    public function static()
    {
        return $this->input('static', true);
    }

    public function dataForTemplate()
    {

        $data = $this->data();

        $data[ 'methodCall' ] = MethodCallRecipe::make()
                                                ->name($this->data('name'))
                                                ->arguments($this->data('arguments'))->render();

        $data[ 'arguments' ] = Recipe::sequence($data[ 'arguments' ]);

        return $data;
    }
}
