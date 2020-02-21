<?php

namespace Afterflow\Recipe;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Validator;

class Recipe
{

    protected $data = [];
    protected $props = [];
    protected $template;

    /**
     * Recipe constructor.
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * @param array $data
     *
     * @return static
     */
    public static function make($data = [])
    {
        return new static($data);
    }

    /**
     * @param array $data
     * @param null $to
     *
     * @return string
     */
    public static function quickRender($data = [], $to = null)
    {
        return static::make($data)->render($to);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public static function quickBuild($data = [])
    {
        return static::make($data)->build();
    }

    /**
     * @param $string
     * @param int $spaces
     *
     * @return string
     */
    public static function indent($string, $spaces = 4)
    {
        $tab = '';
        for ($i = 0; $i < $spaces; $i++) {
            $tab .= ' ';
        }

        return $tab . collect(explode(PHP_EOL, $string))->implode(PHP_EOL . $tab);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function build()
    {

        $this->validateParams();

        if (method_exists($this, 'prepare')) {
            $this->prepare();
        }

        return $this->data;
    }

    /**
     * @param $template
     *
     * @return $this
     */
    public function template($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function with($data)
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * @param null $saveTo
     *
     * @return string
     * @throws \Exception
     */
    public function render($saveTo = null)
    {
        $data = $this->build();

        if (method_exists($this, 'dataForTemplate')) {
            $data = $this->dataForTemplate();
        }

        $eventDispatcher = new Dispatcher(new Container());

        $viewResolver  = new EngineResolver();
        $bladeCompiler = new BladeCompiler(new Filesystem(), '/tmp');

        $viewResolver->register('blade', function () use ($bladeCompiler) {
            return new CompilerEngine($bladeCompiler);
        });

        $viewFinder  = new FileViewFinder(new Filesystem(), [ '/tmp' ]);
        $viewFactory = new Factory($viewResolver, $viewFinder, $eventDispatcher);

        $rendered = $viewFactory->file($this->template, $data)->render();
        if ($saveTo) {
            file_put_contents($saveTo, $rendered);
        }

        return $rendered;
    }

    /**
     * @throws \Exception
     */
    protected function validateParams()
    {
        $rules = [];

        foreach ($this->props as $name => $parameter) {
            if (! is_array($parameter)) {
                $parameter = [
                    'default' => $parameter,
                ];
            }

            if (isset($parameter[ 'rules' ])) {
                $rules[ $name ] = $parameter[ 'rules' ];
            }

            $default       = null;
            $defaultIsNull = true;

            if (isset($parameter[ 'default' ])) {
                $default       = $parameter[ 'default' ];
                $defaultIsNull = false;
            } else {
                try {
                    $parameter[ 'default' ];
                } catch (\Exception $e) {
                    $defaultIsNull = false;
                }
            }


            if (! isset($this->data[ $name ])) {
                if ($defaultIsNull) {
                    $this->data[ $name ] = null;
                } else {
                    $this->data[ $name ] = $default;
                }
            }
        }

        $v = new Validator(new Translator(new ArrayLoader(), 'en'), $this->data, $rules);

        if ($v->fails()) {
            throw new \Exception(collect($v->errors()->all())->implode(PHP_EOL));
        }
    }
}
