<?php

namespace Eli\Vacation;

use Eli\Vacation\Controllers\UsersController;
use Eli\Vacation\Exceptions\NotFoundException;
use JetBrains\PhpStorm\Pure;

class Router
{
    /**
     * @var array
     */
    protected array $routes = [];

    /**
     * Router constructor.
     *
     * @param  Request   $request
     * @param  Response  $response
     */
    #[Pure] public function __construct (public Request $request, public Response $response) {}

    /**
     * @param $path
     * @param $callback
     */
    public function get ($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    /**
     * @param $path
     * @param $callback
     */
    public function post ($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }


    /**
     * @return mixed
     * @throws NotFoundException
     */
    public function resolve (): mixed
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback || !is_array($callback)) {
            throw new NotFoundException();
        }

        $callback[0] = new $callback[0]();
        return call_user_func($callback, $this->request, $this->response);
    }

    public function registerRoutes()
    {
        $this->post('/contact', [UsersController::class, 'register']);
        $this->post('/contact', [UsersController::class, 'login']);
    }
}