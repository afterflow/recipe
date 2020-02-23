<?php

namespace Afterflow\Recipe\Recipes\Laravel\Models;

use Afterflow\Recipe\Recipe;
use Afterflow\Recipe\Recipes\ClassRecipe;
use Afterflow\Recipe\Recipes\ClassVarRecipe;
use Afterflow\Recipe\Recipes\Concerns\MagicSetters;

use function Afterflow\Recipe\arr;
use function Afterflow\Recipe\eol;

class ModelRecipe extends Recipe
{
    use MagicSetters;

    protected $template = __DIR__ . '/../../../../templates/laravel/model.blade.php';

    protected $props = [

        'name' => [
            'rules' => 'required|string',
        ],

        'namespace' => [
            'default' => 'App\Models',
            'rules'   => 'required|string',
        ],

        'extends' => [
            'default' => 'Model',
            'rules'   => 'required|string',
        ],

        'relations' => [
            'default' => [],
            'rules'   => 'array',
        ],

        'guarded' => [
            'default' => [],
            'rules'   => 'array',
        ],

        'fillable' => [
            'default' => [],
            'rules'   => 'array',
        ],

        'dates' => [
            'default' => [],
            'rules'   => 'array',
        ],

        'guarded' => [
            'default' => [],
            'rules'   => 'array',
        ],

        'table'        => [
            'default' => '',
            'rules'   => 'string',
        ],
        'extraContent' => [
            'default' => '',
            'rules'   => 'string',
        ],
        'imports'      => [
            'default' => [
            ],
            'rules'   => 'array',
        ],
        'implements'   => [
            'default' => [],
            'rules'   => 'array',
        ],
        'traits'       => [
            'default' => [],
            'rules'   => 'array',
        ],

    ];



//  public function build() {
//      $mappedData = $this->map( [
//          'name',
//          'namespace',
//          'extends',
//          'imports',
//          'implements',
//          'traits',
//      ] );
//
//      $r = ClassRecipe::make( $mappedData )
//                      ->content(
//
//                          ( $this->data( 'table' ) ?
//                              ClassVarRecipe::make()->name( '$table' )
//                                            ->protected()->value( $this->data( 'table' ) ) : ''
//                          )
//                          . eol( 2 ) .
//                          ( $this->data( 'fillable' ) ?
//                              ClassVarRecipe::make()->protected()->name( '$fillable' )->value( arr( $this->data( 'fillable' ) ) )
//                              :
//                              ClassVarRecipe::make()->protected()->name( '$guarded' )->value( arr( $this->data( 'guarded' ) ) )
//                          ) .
//                          eol( 2 ) .
//                          ( $this->data( 'fillable' ) ?
//                              ClassVarRecipe::make()->protected()->name( '$fillable' )->value( arr( $this->data( 'fillable' ) ) )
//                              :
//                              ClassVarRecipe::make()->protected()->name( '$guarded' )->value( arr( $this->data( 'guarded' ) ) )
//                          ) .
//                          eol( 4 ) .
//                          Recipe::sequence( $this->data( 'relations' ), eol( 2 ) )
//                      );
//
//
//      return $r->build();
//  }
}
