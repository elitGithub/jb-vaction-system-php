<?php


require_once '..' . DIRECTORY_SEPARATOR . 'env.php';
require_once '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$router = new Eli\Vacation\Router(new Eli\Vacation\Request(), new Eli\Vacation\Response());

$router->registerRoutes();

try {
    $router->resolve();
} catch (\Eli\Vacation\Exceptions\NotFoundException $e) {
    $router->response
        ->setSuccess(false)
        ->setMessage($e->getMessage())
        ->sendResponse();
}