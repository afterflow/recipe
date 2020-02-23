<?php

namespace Afterflow\Recipe\Recipes\Laravel\Models\Relations;

use Afterflow\Recipe\Recipe;
use Afterflow\Recipe\Recipes\Concerns\MagicSetters;
use Afterflow\Recipe\Recipes\FunctionRecipe;
use Afterflow\Recipe\Recipes\MethodCallRecipe;

class HasManyRelation extends Recipe
{
    use MagicSetters;

    protected $renderOn = FunctionRecipe::class;

    protected $props = [
        'name'       => [
            'rules' => 'required|string',
        ],
        'related'    => [
            'rules' => 'required|string',
        ],
        'foreignKey' => [
            'default' => '',
            'rules'   => 'string',
        ],
        'farKey'     => [
            'default' => '',
            'rules'   => 'string',
        ],
        'affix'      => [
            'default' => '',
            'rules'   => 'string',
        ],
    ];

    public function build()
    {

        $args = [ $this->data('related') ];
        if ($v = $this->data('foreignKey')) {
            $args[] = $v;
        }
        if ($v = $this->data('farKey')) {
            $args[] = $v;
        }

        return Relation::make()->type('hasMany')
                       ->name($this->name())
                       ->affix($this->affix())
                       ->arguments($args)
                       ->build();
    }
}
