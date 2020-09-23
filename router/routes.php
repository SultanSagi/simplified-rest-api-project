<?php

use DI\Container;
use Doctrine\ORM\EntityManagerInterface;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addGroup('', function (RouteCollector $r) {
        $r->addRoute('GET', '', 'HomeController@index');
        $r->addRoute('GET', '/state/{id:[0-9]+}', 'StateController@getOne');
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

$container = new Container();
$container->set(EntityManagerInterface::class, $entityManager);

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        $response = new JsonResponse(['message' => "Not found"], 404);
        break;

    case Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        $response       = new JsonResponse(['message' => "Method Not Allowed"], 405);
        break;

    case Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars    = $routeInfo[2];
        [$class, $method] = explode("@", $handler, 2);
        $class = "App\\Controllers\\" . $class;

        array_unshift($vars, $request);
        $controller = $container->get($class);
        try {
            $response = $controller->$method(...array_values($vars));
        } catch (Throwable $e) {
            $response = new JsonResponse(['message' => $e->getMessage()], 400);
        }

        break;

    default:
        $response = new JsonResponse(['message' => "Not found"], 404);
}

if ($response) {
    $emitter = new SapiEmitter();
    $emitter->emit($response);
}