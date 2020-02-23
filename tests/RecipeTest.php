<?php

namespace Afterflow\Recipe\Tests;

use Afterflow\Recipe\Recipe;
use Afterflow\Recipe\Recipes\ClassRecipe;
use Afterflow\Recipe\Recipes\ClassVarRecipe;
use Afterflow\Recipe\Recipes\ConstructorRecipe;
use Afterflow\Recipe\Recipes\FunctionRecipe;
use Afterflow\Recipe\Recipes\Laravel\Models\ModelRecipe;
use Afterflow\Recipe\Recipes\Laravel\Models\Relations\BelongsToRelation;
use Afterflow\Recipe\Recipes\Laravel\Models\Relations\HasManyRelation;
use Afterflow\Recipe\Recipes\MethodCallRecipe;
use PHPUnit\Framework\TestCase;

use function Afterflow\Recipe\eol;
use function Afterflow\Recipe\q;
use function Afterflow\Recipe\qq;

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
        $data = ClassRecipe::make()->namespace('App')->name('User')
                           ->extends('Authenticatable')
                           ->traits([ 'HasApiTokens', 'Notifiable' ])
                           ->imports([
                               'Illuminate\Foundation\Auth\User as Authenticatable',
                               'Illuminate\Notifications\Notifiable',
                               'Laravel\Passport\HasApiTokens',
                           ])
                           ->implements([ 'SomeInterface', 'OtherInterface' ])
                           ->render();

        $this->assertStringContainsString('class User', $data);
        $this->assertStringContainsString('implements SomeInterface', $data);
    }

    public function testFunctionRecipe()
    {
        $data = ( new FunctionRecipe() )
            ->with([
                'name'       => 'cards',
                'visibility' => 'public',
                'return'     => '$this->hasMany(Card::class)',
            ])
            ->render();
        $this->assertStringContainsString('function cards()', $data);
    }

    public function testValidation()
    {

        try {
            ClassVarRecipe::make()->name([ 'something' ])->render();
        } catch (\Exception $e) {
            $this->assertStringContainsString('must be string', $e->getMessage());
        }

        $this->expectExceptionMessage('The name field is required.');
        ClassRecipe::make()->namespace([ 'array' ])->render();
    }

    public function testMethodCall()
    {
        $data = MethodCallRecipe::make()->assignTo('$user =')
                                ->on('auth()->')
                                ->name('user')
                                ->arguments([ '$one', '$two' ])->render();

        $this->assertEquals($data, '$user = auth()->user($one, $two)');
    }

    public function testFluentFunctionRecipe()
    {
        $data = FunctionRecipe::make()->name('cards')->public()
                              ->return(
                                  MethodCallRecipe::make()->on('$this->')
                                                  ->name('hasMany')
                                                  ->arguments([ 'Card::class' ])->render()
                              )->render();
        $this->assertStringContainsString('function cards()', $data);
    }

    public function testFluentClassVarRecipe()
    {

        $data = ClassVarRecipe::make()->name('$name')
                              ->protected()
                              ->value(qq('Vlad'))
                              ->docBlock('// First Name')
                              ->render();

        $this->assertStringContainsString('protected $name = "Vlad";', $data);
    }

    public function testHasMany()
    {
        $data = HasManyRelation::make()->name('cards')->related('Card::class')->foreignKey(qq('user_id'))->render();
        $this->assertStringContainsString('return $this->hasMany(Card::class, "user_id");', $data);
    }

    public function testModel()
    {
        $data = ModelRecipe::make()->name('User')
                           ->table(q('users'))
                           ->extends('Authenticatable')
                           ->imports([
                               'Illuminate\Foundation\Auth\User as Authenticatable',
                               'Illuminate\Notifications\Notifiable',
                           ])
                           ->relations([
                               HasManyRelation::make()->name('cards')->related('\App\Models\Card::class'),
                               BelongsToRelation::make()->name('profile')->related('\App\Models\Profile::class'),
                           ])
                           ->render();

        //      file_put_contents( 'build/User.php', $data );
        $this->assertStringContainsString('class User extends Authenticatable', $data);
    }

    public function testNestedRecipes()
    {

        /**
         * This recipe nests other recipes and shows alternative syntax to pass data through constructor
         */
        $data = ClassRecipe::make()->namespace('App')->name('User')->content(
        /**
         * See ClassVarRecipe to learn how to render things without template
         */
            Recipe::sequence([
                ClassVarRecipe::make()->protected()->name('$name')->docBlock('// First Name')->render(),
                ClassVarRecipe::make()->protected()->name('$lastName')->docBlock('// Last Name')->render(),
                /**
                 * See ClassVarRecipe to learn how to filter data before render
                 */
                ConstructorRecipe::make()->arguments([
                    'string $name',
                    'string $lastName',
                ])->body('$this->name = $name;' . eol() . '$this->lastName = $lastName;')->render(),
                FunctionRecipe::make()->name('getLastName')->return('$this->lastName;')->render(),
                FunctionRecipe::make()->name('getName')->return('$this->name;')->render(),
            ], eol(2))
        )->render();

        $this->assertStringContainsString('function getLastName', $data);
        $this->assertStringContainsString('function getName', $data);
        $this->assertStringContainsString('protected $lastName', $data);
    }
}
