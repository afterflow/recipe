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
            'rules' => 'required',
        ],
        'visibility' => null,
        'value'      => [
        ],
        'static'     => false,
        'const'      => false,
        'docBlock'   => '',
    ];

    public function name($name)
    {
        $this->data[ 'name' ] = $name;

        return $this;
    }

    public function value($value)
    {
        $this->data[ 'value' ] = $value;

        return $this;
    }

    public function static()
    {
        $this->data[ 'static' ] = true;

        return $this;
    }

    public function render($to = null)
    {
        $data = $this->build();

        $string = '';

        if ($v = $data[ 'docBlock' ]) {
            $string .= $v . PHP_EOL;
        }

        if ($v = $data[ 'visibility' ]) {
            $string .= $v . ' ';
        }

        if ($data[ 'static' ]) {
            $string .= 'static ';
        }

        if ($data[ 'const' ]) {
            $string .= 'const';
        }

        $string .= $data[ 'name' ];
        if ($data[ 'value' ]) {
            $string .= ' = ' . $data[ 'value' ];
        }

        $string .= ';';

        if ($to) {
            file_put_contents($to, $string);
        }

        return $string;
    }
}
