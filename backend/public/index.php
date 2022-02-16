<?php


use Eli\Vacation\Route;
use Eli\Vacation\Router;

require_once '..' . DIRECTORY_SEPARATOR . 'env.php';
require_once '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS", Eli\Vacation\Helpers\ResponseCodes::HTTP_OK);
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if (strtolower($_SERVER['REQUEST_METHOD']) === 'options') {
    http_response_code(Eli\Vacation\Helpers\ResponseCodes::HTTP_OK);
    header('HTTP/1.1 200 OK', Eli\Vacation\Helpers\ResponseCodes::HTTP_OK);
    die('0');
}

$router = new Router([
    new Route('home-page', '/', [Eli\Vacation\Controllers\SiteController::class, 'index'], ['GET']),
    new Route('home-page', '', [Eli\Vacation\Controllers\SiteController::class, 'index'], ['GET']),
    new Route('home-page', '/vacation-v2/backend', [Eli\Vacation\Controllers\SiteController::class, 'index'], ['GET']),
    new Route('login', '/login', [Eli\Vacation\Controllers\UsersController::class, 'validateToken'], ['GET']),
    new Route('create-vacation', '/vacation-v2/backend/vacations/vacation', [Eli\Vacation\Controllers\VacationsController::class, 'createVacation'], ['POST']),
    new Route('vacation-v2/backend/vacation', '/vacation-v2/backend/vacation/{id}', [Eli\Vacation\Controllers\VacationsController::class, 'getVacation'], ['GET']),
]);

try {
    // Example
    // \Psr\Http\Message\ServerRequestInterface
    //$route = $router->match(ServerRequestFactory::fromGlobals());
    // OR

    // $_SERVER['REQUEST_URI'] = '/api/articles/2'
    // $_SERVER['REQUEST_METHOD'] = 'GET'
    $route = $router->matchFromPath($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

    $parameters = $route->getParameters();
    // $arguments = ['id' => 2]
    $arguments = $route->getVars();

    $controllerName = $parameters[0];
    $methodName = $parameters[1] ?? null;

    $controller = new $controllerName();
    if (!is_callable($controller)) {
        $controller = [$controller, $methodName];
    }

    call_user_func_array($controller, $arguments);
} catch (Exception $e) {
    echo $e->getMessage();
    header("HTTP/1.0 404 Not Found");
}