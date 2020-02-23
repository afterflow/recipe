<?php

namespace Afterflow\Recipe\Recipes;

use Afterflow\Recipe\Recipe;

/**
 * Class ClassRecipe
 * @package Afterflow\Recipe\Recipes
 */
class ClassRecipe extends Recipe
{

    /**
     * @var string
     */
    protected $template = __DIR__ . '/../../templates/class.blade.php';

    /**
     * @var array
     */
    protected $props = [
        'name'       => [
            'rules' => 'required|string',
        ],
        'extends'    => [
            'default' => '',
            'rules' => 'string',
        ],
        'namespace'  => [
            'rules' => 'string',
        ],
        'content'    => [
            'default' => '',
            'rules'   => 'string',
        ],
        'imports'    => [
            'default' => [],
            'rules'   => 'array',
        ],
        'implements' => [
            'default' => [],
            'rules'   => 'array',
        ],
        'traits'     => [
            'default' => [],
            'rules'   => 'array',
        ],
    ];

    /**
     * @param $value
     *
     * @return $this
     */
    public function namespace($value)
    {
        return $this->input('namespace', $value);
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function content($value)
    {
        return $this->input('content', $value);
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function name($value)
    {
        return $this->input('name', $value);
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function traits($value)
    {
        return $this->input('traits', $value);
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function imports($value)
    {
        return $this->input('imports', $value);
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function implements($value)
    {
        return $this->input('implements', $value);
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function extends($value)
    {
        return $this->input('extends', $value);
    }
}
