<?php

namespace Afterflow\Recipe\Recipes;

use Afterflow\Recipe\Recipe;

class ClassVarRecipe extends Recipe
{

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
