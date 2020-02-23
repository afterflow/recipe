<?php

namespace Afterflow\Recipe\Recipes;

use Afterflow\Recipe\Recipe;
use Afterflow\Recipe\Recipes\Concerns\HasDocBlock;
use Afterflow\Recipe\Recipes\Concerns\HasVisibility;

class ClassVarRecipe extends Recipe
{
    use HasDocBlock;
    use HasVisibility;

    protected $props = [
        'name'       => [
            'rules' => 'required|string',
        ],
        'visibility' => [
            'rules' => 'string|in:public,private,protected',
        ],
        'value'      => [
            'default' => '',
            'rules' => 'string',
        ],
        'static'     => [
            'default' => false,
            'rules'   => 'boolean',
        ],
        'const'      => [
            'default' => false,
            'rules'   => 'boolean',
        ],
        'docBlock'   => [
            'default' => '',
            'rules'   => 'string',
        ],
    ];

    public function name($value)
    {
        return $this->input('name', $value);
    }

    public function value($value)
    {
        return $this->input('value', $value);
    }

    public function const()
    {
        return $this->input('const', true);
    }

    public function static()
    {
        return $this->input('static', true);
    }

    public function render()
    {

        $string = '';

        if ($v = $this->data('docBlock')) {
            $string .= $v . PHP_EOL;
        }

        if ($v = $this->data('visibility')) {
            $string .= $v . ' ';
        }

        if ($this->data('static')) {
            $string .= 'static ';
        }

        if ($this->data('const')) {
            $string .= 'const ';
        }

        $string .= $this->data('name');
        if ($v = $this->data('value')) {
            $string .= ' = ' . $v;
        }

        $string .= ';';

        return $string;
    }
}
