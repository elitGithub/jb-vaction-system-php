<?php


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
$router = new Eli\Vacation\Router(new Eli\Vacation\Request(), new Eli\Vacation\Response());

$router->registerRoutes();

try {
    $router->resolve();
} catch (Eli\Vacation\Exceptions\NotFoundException $e) {
    $router->response
        ->setSuccess(false)
        ->setCode($e->getCode())
        ->setMessage($e->getMessage())
        ->sendResponse();
}