<?php

namespace Eli\Vacation;

use Eli\Vacation\Exceptions\NotFoundException;
use JetBrains\PhpStorm\Pure;

class Router_backup
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
        $this->appRoot = getenv('APP_URL') . '/';
    }

    /**
     * @param $path
     * @param $callback
     *
     * @return Router
     */
    public function get ($path, $callback): static
    {
        $this->routes['get'][$path] = $callback;
        return $this;
    }

    /**
     * @param $path
     * @param $callback
     *
     * @return Router
     */
    public function post ($path, $callback): static
    {
        $this->routes['post'][$path] = $callback;
        return $this;
    }

    /**
     * @param $path
     * @param $callback
     *
     * @return $this
     */
    public function put ($path, $callback): static
    {
        $this->routes['put'][$path] = $callback;
        return $this;
    }

    /**
     * @param $path
     * @param $callback
     *
     * @return $this
     */
    public function delete ($path, $callback): static
    {
        $this->routes['delete'][$path] = $callback;
        return $this;
    }

    private function routeHandling ()
    {
        $method = $this->request->method();
        if (is_array($this->routes[$method])) {
            $list = array_keys($this->routes[$method]);
            foreach ($list as $route) {
                $this->routeHasParams($route);
            }
        }
    }

    private function routeHasParams ($route)
    {
        preg_match('/{(.*?)}/', $route, $matches);
        $segments = explode('/', $route);
        if (!empty($matches[1])) {
            if ($this->request->isGet()) {
                $_GET[$matches[1]] = $segments[1];
            }
        }
    }

    /**
     * @return mixed
     * @throws NotFoundException
     */
    public function resolve (): mixed
    {
        $path = str_ireplace($this->appRoot, '', $this->request->getPath());
        $method = $this->request->method();
        $this->routeHandling();
        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback || !is_array($callback)) {
            throw new NotFoundException();
        }

        $callback[0] = new $callback[0]();
        return call_user_func_array($callback, [$this->request, $this->response]);
    }

    public function registerRoutes ()
    {
        $this->get('', [Controllers\SiteController::class, 'index'])
             ->get('/', [Controllers\SiteController::class, 'index'])
             ->get('login', [Controllers\UsersController::class, 'validateToken'])
             ->get('vacation/{id}', [Controllers\VacationsController::class, 'getVacation'])
             ->post('vacation', [Controllers\VacationsController::class, 'createVacation'])
             ->post('vacation/{id}', [Controllers\VacationsController::class, 'updateVacation'])
             ->put('vacation/{id}', [Controllers\VacationsController::class, 'updateVacation'])
             ->delete('vacation/{id}', [Controllers\VacationsController::class, 'updateVacation'])
             ->post('register', [Controllers\UsersController::class, 'register'])
             ->post('login', [Controllers\UsersController::class, 'login'])
             ->put('login', [Controllers\UsersController::class, 'login']);
    }
}