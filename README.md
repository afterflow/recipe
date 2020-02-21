# Afterflow Recipe

Recipe is a generator framework built with Laravel components that allows you to generate anything based on the 
provided data and templates.

The primary goal of this library is to provide tooling required to create your generators.

## Requirements

Although it's relying on Blade templating system from Laravel Framework, 
this library does not require Laravel, it only pulls some of it's components.

This means you can safely include it in your own framework-agnostic composer package.

## Installation

```bash
composer require afterflow/recipe
```

## Basic Usage

Given we have a simple Blade template:

```blade
{{-- templates/user.blade.php --}}

Name: {{$name}}
Last Name: {{ $last_name }}

```

Let's compile a string with Recipe:

```php

$recipe = new \Afterflow\Recipe\Recipe();
$data   = $recipe->with([ 'name' => 'Vlad', 'last_name' => 'Libre' ])
                 ->template(__DIR__ . '/templates/user.blade.php')
                 ->render();

```

Returns:

```

Name: Vlad
Last Name: Libre

```

So basically we just compiled a Blade template with the given data. But that's not actually the point. Let's now see some advanced usage.

## Custom Recipe classes

Let's build a simple custom recipe we can reuse or even nest on other recipes.
It will create a class from a stub and return the source code.

Template:

```blade
{{--templates/class.blade.php--}}

{{-- Otherwise this file will be treated as PHP script--}}
{!! '<'.'?php' !!}

@unless(empty( $namespace ))
namespace {{ $namespace }};
@endunless

@unless(empty( $imports ))
    @foreach( $imports as $import)
import {{ $import }};
    @endforeach
@endunless

class {{ $name }} {{ isset($extends) ? 'extends '. $extends : '' }} {{ !empty($implements) ? 'implements '. collect($implements)->implode(', ') : '' }}
{
@unless(empty($traits))
    use {{ collect($traits)->implode(', ') }};
@endunless

@isset($content)
{{--This function indents each line of $content string with 4 spaces--}}
{!! \Afterflow\Recipe\Recipe::indent($content,4) !!}
@endisset
}

```

Recipe:

```php

<?php

namespace Afterflow\Recipe\Recipes;

use Afterflow\Recipe\Recipe;

class ClassRecipe extends Recipe
{

    protected $template = __DIR__ . '/../../templates/class.blade.php';

    protected $props = [
        'name'    => [
            'rules' => 'required',
        ],
        'imports' => [
            'default' => [],
        ],
    ];
}
```

Usage:

```php

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

```

Few new things happen here since we are now using our own `ClassRecipe` class that extends `Recipe`.
This allows us to define template inside the class and have a shorter usage syntax.

Here you can notice that we're defining a new `$props` variable which is somewhat similar to what VueJs uses in it's components.

Two important things happen here:

First, we added some validation telling Recipe that `name` data property is mandatory in this recipe. 
You can define validation rules just like you normally would in your Laravel application - that's the same thing.

Second, we're setting default value for imports.
Those defaults will be applied if the user does not provide anything as the input.

### Building data without rendering

Sometimes it may be useful to only build data off of the user input and defined props without template at all:

```php

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
])->build();

```

If you call `build()` instead of `render()` you'll get back the normalized data after applying props on original input.
You can use this data in other recipes to create compound generators.

### Alternative syntax

A shorter syntax might be useful when building complex nested recipes.

```php

// Full syntax
$recipe = (new ClassRecipe())->with($data)->render();

// Pass data into constructor:
$recipe = (new ClassRecipe($data))->render();

// If your recipe defines a template or a custom render() function:
$string = ClassRecipe::quickRender($data);

// Compile data only:
$data = ClassRecipe::quickBuild($data);

```

### Prepare template data before rendering

Sometimes it's useful to transform the data before sending it to Blade compiler. If your recipe has `dataForTemplate()` method,
it's return value will be used as the data for the template.

```php

<?php

namespace Afterflow\Recipe\Recipes;

use Afterflow\Recipe\Recipe;

class FunctionRecipe extends Recipe
{

    protected $template = __DIR__ . '/../../templates/function.blade.php';

    protected $props = [
        'name'       => [
            'rules' => 'required',
        ],
        'arguments'  => [],
        // ...
    ];

    public function dataForTemplate()
    {

        $data = $this->data;

        $data['arguments'] = collect($data['arguments'])->implode(', ');

        return $data;
    }
}

```

### Custom rendering

By overriding the `render()` method in your recipe you can create recipes without template or define any other custom rendering logic.
Just make sure to call `$this->build()` inside to apply props to the input.

```php

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
```

## Nested recipes

Now let's see how powerful this can be:

```php

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

```

This will produce:

```php
<?php

namespace App;


class User  
{

    // First Name
    protected $name = "Vlad";
    
    // Last Name
    protected $lastName;
    
    function __construct(string $name, string $lastName)
    {
        $this->name = $name;
        $this->lastName = $lastName;
    }
    
    function getLastName()
    {
        return $this->lastName;
    }
    
    function getName()
    {
        return $this->name;
    }
    
}

```

Now you can extend or nest the `ClassRecipe` to make a Laravel Model recipe and create a console command to generate a model or do any other crazy stuff.

### Built in recipes you can use:

- ClassRecipe
- ClassVarRecipe
- FunctionRecipe

