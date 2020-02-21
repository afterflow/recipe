<?php

namespace Afterflow\Recipe\Recipes;

use Afterflow\Recipe\Recipe;

class FunctionRecipe extends Recipe {

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

    public function arguments( $value ) {
        $this->data[ 'arguments' ] = [];

        return $this;
    }

    public function body( $name ) {
        $this->data[ 'body' ] = $name;

        return $this;
    }

    public function name( $name ) {
        $this->data[ 'name' ] = $name;

        return $this;
    }

    public function visibility( $value ) {
        $this->data[ 'visibility' ] = $value;

        return $this;
    }

    public function protected() {

        return $this->visibility( 'protected' );
    }

    public function private() {

        return $this->visibility( 'private' );
    }

    public function public() {

        return $this->visibility( 'public' );
    }

    public function return( $v ) {
        $this->data[ 'return' ] = $v;

        return $this;
    }

    public function abstract() {
        $this->data[ 'abstract' ] = true;

        return $this;
    }

    public function static() {
        $this->data[ 'static' ] = true;

        return $this;
    }

    public function docBlock( $value ) {
        $this->data[ 'docBlock' ] = $value;

        return $this;
    }

    public function dataForTemplate() {

        $data = $this->data;

        $data[ 'arguments' ] = collect( $data[ 'arguments' ] )->implode( ', ' );

        return $data;
    }
}
