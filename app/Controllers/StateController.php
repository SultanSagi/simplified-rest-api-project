<?php

namespace App\Controllers;

use App\Services\StateService;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;

class StateController
{
    private $stateService;

    public function __construct(StateService $stateService)
    {
        $this->stateService = $stateService;
    }

    public function getOne(ServerRequest $request, int $id)
    {
        $state = $this->stateService->findById($id);
        return new JsonResponse(["status" => "success", "state" => $state->toArray()]);
    }
}