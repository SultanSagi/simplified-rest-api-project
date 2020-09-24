<?php

namespace App\Controllers;

use App\Services\CountryService;
use App\Services\CountyService;
use App\Services\StateService;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;

class CountyController
{
    private $countyService;
    private $stateService;

    public function __construct(CountyService $countyService, StateService $stateService)
    {
        $this->countyService = $countyService;
        $this->stateService = $stateService;
    }

    public function getOne(ServerRequest $request, int $id)
    {
        $county = $this->countyService->findById($id);
        return new JsonResponse(["status" => "success", "county" => $county->toArray()]);
    }

    public function create(ServerRequest $request)
    {
        $data = json_decode($request->getBody()->getContents());

        $this->countyService->create($data->name, $data->tax_rate, $data->tax_amount, $this->stateService->findById($data->state_id));

        return new JsonResponse(['success' => true, 'message' => 'County created']);
    }
}