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
        'arguments'  => [],
        'visibility' => null,
        'body'       => '',
        'static'     => false,
        'abstract'   => false,
        'docBlock'   => '',
        'return'     => [],
    ];

    public function arguments($value)
    {
        $this->data[ 'arguments' ] = $value;

        return $this;
    }

    public function body($name)
    {
        $this->data[ 'body' ] = $name;

        return $this;
    }

    public function name($name)
    {
        $this->data[ 'name' ] = $name;

        return $this;
    }

    public function return($v)
    {
        $this->data[ 'return' ] = $v;

        return $this;
    }

    public function abstract()
    {
        $this->data[ 'abstract' ] = true;

        return $this;
    }

    public function static()
    {
        $this->data[ 'static' ] = true;

        return $this;
    }

    public function dataForTemplate()
    {

        $data = $this->data;

        $data[ 'methodCall' ] = MethodCallRecipe::make()
                                                ->name($this->data[ 'name' ])
                                                ->arguments($data[ 'arguments' ])->render();
//        dd($data);

        $data[ 'arguments' ] = collect($data[ 'arguments' ])->implode(', ');

        return $data;
    }
}
