<?php

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addGroup('/', function (RouteCollector $r) {
        $r->addRoute('GET', '', 'HomeController@index');
    });
});


$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri        = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

$request = ServerRequestFactory::fromGlobals();

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        echo "404 Not Found";
        break;

    case Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo "405 Method Not Allowed";
        break;

    case Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars    = $routeInfo[2];
        [$class, $method] = explode("@", $handler, 2);
        $class = "App\\Controllers\\" . $class;

        array_unshift($vars, $request);
        $response = call_user_func_array([new  $class, $method], $vars);
        break;

    default:
        echo "404 Not Found";
}

if ($response) {
    $emitter = new SapiEmitter();
    $emitter->emit($response);
}