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

    public function docBlock($value)
    {
        $this->data[ 'docBlock' ] = $value;

        return $this;
    }

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
