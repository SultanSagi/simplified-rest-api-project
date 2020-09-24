<?php

namespace App\Controllers;

use App\Services\CountryService;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;

class CountryController
{
    private $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function getOne(ServerRequest $request, int $id)
    {
        $country = $this->countryService->findById($id);
        return new JsonResponse(["status" => "success", "country" => $country->toArray()]);
    }

    public function create(ServerRequest $request)
    {
        $data = json_decode($request->getBody()->getContents());

        $this->countryService->create($data->name);

        return new JsonResponse(['success' => true, 'message' => 'Country created']);
    }
}