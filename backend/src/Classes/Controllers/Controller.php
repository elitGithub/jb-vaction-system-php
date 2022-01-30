<?php

namespace Eli\Vacation\Controllers;

use Eli\Vacation\Middlewares\BaseMiddleware;
use Eli\Vacation\Response;
use JetBrains\PhpStorm\Pure;

class Controller
{

    public string $layout = 'main';
    public string $action = '';
    protected Response $response;

    #[Pure] public function __construct () {
        $this->response = new Response();
    }

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