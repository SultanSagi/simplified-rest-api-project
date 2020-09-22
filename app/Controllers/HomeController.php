<?php

namespace App\Controllers;

use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;

class HomeController
{
    public function index(ServerRequest $request)
    {
        return new JsonResponse([
            "data" => "Hello from index"
        ]);
    }
}