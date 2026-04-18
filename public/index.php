<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Config;
use App\Core\Database\Database;
use App\Core\DI\Container;
use App\Core\Http\Request;
use App\Core\Http\Router;
use App\Core\Log\Logger;
use App\Core\Service\ExceptionHandler;

require dirname(__DIR__) . '/vendor/autoload.php';

$rootPath = dirname(__DIR__);
$config = new Config($rootPath . '/config');
$container = new Container();
$request = Request::fromGlobals();
$db = new Database(
    (string) App::env('DB_HOST', 'db'),
    (int) App::env('DB_PORT', 3306),
    (string) App::env('DB_DATABASE', 'todo_list'),
    (string) App::env('DB_USERNAME', 'app'),
    (string) App::env('DB_PASSWORD', 'secret'),
);
$logger = new Logger($rootPath . '/var/log/app.log', (string) App::env('APP_LOG_LEVEL', 'debug'));
$router = new Router();
$router->registerControllers($rootPath . '/src/Controller', 'App\\Controller');
$exceptionHandler = new ExceptionHandler();

App::init($container, $config, $request, $db, $logger, $router, $rootPath);
try {
    $response = App::$router->dispatch();
} catch (\Throwable $exception) {
    $response = $exceptionHandler->handle($exception);
}

$response->send();
