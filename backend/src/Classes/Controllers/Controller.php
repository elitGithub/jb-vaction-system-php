<?php

namespace Eli\Vacation\Controllers;

use Eli\Vacation\Middlewares\BaseMiddleware;
use Eli\Vacation\Request;
use Eli\Vacation\Response;
use Eli\Vacation\Session;
use JetBrains\PhpStorm\Pure;

class Controller
{

    public string $layout = 'main';
    public string $action = '';
    protected Response $response;
    protected Session $session;
    protected Request $request;

    public function __construct () {
        $this->response = new Response();
        $this->session = new Session();
        $this->request = new Request();
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