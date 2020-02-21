<?php

namespace Afterflow\Recipe\Recipes;

use Afterflow\Recipe\Recipe;

/**
 * Class MethodCallRecipe
 */
class MethodCallRecipe extends Recipe
{

    /**
     * @var array
     */
    protected $props = [
        'name'      => [
            'rules' => 'required',
        ],
        'arguments' => [],
        'object'    => null,
        'assignTo'  => null,
    ];

    /**
     * @param $value
     *
     * @return $this
     */
    public function arguments($value)
    {
        $this->data[ 'arguments' ] = $value;

        return $this;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function assignTo($name)
    {
        $this->data[ 'assignTo' ] = $name;

        return $this;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function on($name)
    {
        $this->data[ 'object' ] = $name;

        return $this;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function name($name)
    {
        $this->data[ 'name' ] = $name;

        return $this;
    }


    /**
     * @param null $to
     *
     * @return string
     */
    public function render($to = null)
    {
        $data = $this->build();

        $string = '';

        if ($v = $data[ 'assignTo' ]) {
            $string .= $v . ' ';
        }

        if ($v = $data[ 'object' ]) {
            $string .= $v . '';
        }

        $string .= $data[ 'name' ] . '(';

        $args = collect($data[ 'arguments' ])->implode(', ');

        $string .= $args;

        $string .= ')';

        if ($to) {
            file_put_contents($to, $string);
        }

        return $string;
    }
}
