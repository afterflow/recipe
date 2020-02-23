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
        'arguments' => [
            'default' => [],
            'rules'   => 'array',
        ],
        'object'    => [
            'default' => '',
            'rules'   => 'string',
        ],
        'on'        => [
            'rules' => 'string',
        ],
        'assignTo'  => null,
    ];

    /**
     * @param $value
     *
     * @return $this
     */
    public function arguments($value)
    {
        return $this->input('arguments', $value);
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function assignTo($name)
    {
        return $this->input('assignTo', $name);
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function on($name)
    {
        return $this->input('object', $name);

        return $this;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function name($name)
    {
        return $this->input('name', $name);
    }


    /**
     * @param null $to
     *
     * @return string
     */
    public function render()
    {

        $string = '';

        if ($v = $this->data('assignTo')) {
            $string .= $v . ' ';
        }

        if ($v = $this->data('object')) {
            $string .= $v . '';
        }

        $string .= $this->data('name') . Recipe::array($this->data('arguments'), [ '(', ')' ]);

        return $string;
    }
}
