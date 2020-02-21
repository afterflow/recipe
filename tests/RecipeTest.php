<?php

namespace Afterflow\Recipe\Tests;

use Afterflow\Recipe\Recipe;
use Afterflow\Recipe\Recipes\ClassRecipe;
use Afterflow\Recipe\Recipes\ClassVarRecipe;
use Afterflow\Recipe\Recipes\FunctionRecipe;
use PHPUnit\Framework\TestCase;

class RecipeTest extends TestCase
{

    public function testBuild()
    {
        $recipe = new Recipe();
        $data   = $recipe->with([ 'name' => 'Vlad', 'last_name' => 'Libre' ])->build();

        $this->assertEquals('Vlad', $data[ 'name' ]);
    }

    public function testRender()
    {
        $recipe = new Recipe();
        $data   = $recipe->with([ 'name' => 'Vlad', 'last_name' => 'Libre' ])
                         ->template(__DIR__ . '/templates/user.blade.php')
                         ->render()
            /**//**/
        ;

        $this->assertStringContainsString('Name: Vlad', $data);
    }

    public function testClassRecipe()
    {
        $data = ( new ClassRecipe() )->with([
            'namespace' => 'App',
            'name'      => 'User',
            'extends'   => 'Authenticatable',

            'imports' => [
                'Illuminate\Foundation\Auth\User as Authenticatable',
                'Illuminate\Notifications\Notifiable',
                'Laravel\Passport\HasApiTokens',
            ],

            'traits'     => [
                'HasApiTokens',
                'Notifiable',
            ],
            'implements' => [ 'SomeInterface', 'OtherInterface' ],
        ])->render();

        $this->assertStringContainsString('class User', $data);
    }

    public function testFunctionRecipe()
    {
        $data = ( new FunctionRecipe() )
            ->with([
                'name'       => 'cards',
                'visibility' => 'public',
                'arguments'  => [
                ],
                'return'     => '$this->hasMany(Card::class)',
            ])
            ->render();
        $this->assertStringContainsString('function cards()', $data);
    }

    public function testNestedRecipes()
    {

        /**
         * This recipe nests other recipes and shows alternative syntax to pass data through constructor
         */
        $data = ( new ClassRecipe([
            'namespace' => 'App',
            'name'      => 'User',
            'content'   =>

            /**
             * See ClassVarRecipe to learn how to render things without template
             */
                ( new ClassVarRecipe([
                    'name'       => '$name',
                    'visibility' => 'protected',
                    'docBlock'   => '// First Name',
                    'value'      => '"Vlad"',
                ]) )->render()
                . PHP_EOL . PHP_EOL .
                /**
                 * Alternative syntax if the recipe has template
                 */
                ClassVarRecipe::quickRender([
                    'name'       => '$lastName',
                    'visibility' => 'protected',
                    'docBlock'   => '// Last Name',
                ])
                . PHP_EOL . PHP_EOL .
                /**
                 * See ClassVarRecipe to learn how to filter data before render
                 */
                FunctionRecipe::quickRender([
                    'name'      => '__construct',
                    'arguments' => [ 'string $name', 'string $lastName', ],
                    'body'      => '$this->name = $name;' . PHP_EOL . '$this->lastName = $lastName;',
                ])
                . PHP_EOL .
                FunctionRecipe::quickRender([ 'name' => 'getLastName', 'return' => '$this->lastName;', ])
                . PHP_EOL .
                FunctionRecipe::quickRender([ 'name' => 'getName', 'return' => '$this->name;', ]),

        ]) )->render();

        die($data);
        $this->assertStringContainsString('function getLastName', $data);
        $this->assertStringContainsString('function getName', $data);
        $this->assertStringContainsString('protected $lastName', $data);
    }
}
