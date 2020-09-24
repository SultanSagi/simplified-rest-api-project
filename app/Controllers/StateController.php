<?php

namespace App\Controllers;

use App\Services\CountryService;
use App\Services\StateService;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;

class StateController
{
    private $stateService;
    private $countryService;

    public function __construct(StateService $stateService, CountryService $countryService)
    {
        $this->stateService = $stateService;
        $this->countryService = $countryService;
    }

    public function getOne(ServerRequest $request, int $id)
    {
        $state = $this->stateService->findById($id);
        return new JsonResponse(["status" => "success", "state" => $state->toArray()]);
    }

    public function create(ServerRequest $request)
    {
        $data = json_decode($request->getBody()->getContents());

        $this->stateService->create($data->name, $this->countryService->findById($data->country_id));

        return new JsonResponse(['success' => true, 'message' => 'State created']);
    }
}