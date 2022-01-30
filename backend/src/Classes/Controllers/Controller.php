<?php

namespace Eli\Vacation\Controllers;

use Eli\Vacation\Middlewares\BaseMiddleware;

class Controller
{

    public string $layout = 'main';
    public string $action = '';

    /**
     * @var BaseMiddleware[]
     */
    protected array $middlewares = [];

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function registerMiddleware(BaseMiddleware $middlewares)
    {
        $this->middlewares[] = $middlewares;
    }

    /**
     * @return BaseMiddleware[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}