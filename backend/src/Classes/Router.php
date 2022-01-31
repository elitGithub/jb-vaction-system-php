<?php

namespace Eli\Vacation;

use Eli\Vacation\Exceptions\NotFoundException;
use JetBrains\PhpStorm\Pure;

class Router
{
    /**
     * @var array
     */
    protected array $routes = [];
    private string | array | false $appRoot;

    /**
     * Router constructor.
     *
     * @param  Request   $request
     * @param  Response  $response
     */
    #[Pure] public function __construct (public Request $request, public Response $response)
    {
        $this->appRoot = getenv('REQUEST_ROOT');
    }

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
        $path = str_ireplace($this->appRoot, '', $this->request->getPath());
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback || !is_array($callback)) {
            throw new NotFoundException();
        }

        $callback[0] = new $callback[0]();
        return call_user_func($callback, $this->request, $this->response);
    }

    public function registerRoutes ()
    {
        $this->get('', [Controllers\SiteController::class, 'index']);
        $this->get('/', [Controllers\SiteController::class, 'index']);
        $this->get('api/users/login', [Controllers\UsersController::class, 'validateToken']);
        $this->post('api/users/register', [Controllers\UsersController::class, 'register']);
        $this->post('api/users/login', [Controllers\UsersController::class, 'login']);
    }
}