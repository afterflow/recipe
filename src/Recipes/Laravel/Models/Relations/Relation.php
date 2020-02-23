<?php

namespace Afterflow\Recipe\Recipes\Laravel\Models\Relations;

use Afterflow\Recipe\Recipe;
use Afterflow\Recipe\Recipes\Concerns\MagicSetters;
use Afterflow\Recipe\Recipes\FunctionRecipe;
use Afterflow\Recipe\Recipes\MethodCallRecipe;

class Relation extends Recipe
{
    use MagicSetters;

    protected $renderOn = FunctionRecipe::class;

    protected $props = [
        'name'      => [
            'rules' => 'required|string',
        ],
        'type'      => [
            'rules' => 'required|string',
        ],
        'affix'     => [
            'default' => '',
            'rules'   => 'string',
        ],
        'method'    => [
            'rules' => 'required|string',
        ],
        'arguments' => [
            'default' => [],
            'rules'   => 'array',
        ],
    ];


    public function build()
    {

        return FunctionRecipe::make()->name($this->data('name'))->public()
                             ->return('$this->' .
                                       MethodCallRecipe::make()->name($this->data('type'))->arguments($this->arguments())->render()
                                       . $this->data('affix') . ';')->build();
    }
}
