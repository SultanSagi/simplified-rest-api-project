<?php

namespace App\Services;

use App\Entities\County;
use App\Entities\State;
use App\Repositories\CountyRepository;

class CountyService
{
    private $countyRepository;

    public function __construct(CountyRepository $countyRepository)
    {
        $this->countyRepository = $countyRepository;
    }

    /**
     * Fetch county by id
     *
     * @param int $id
     * @return State|null
     */
    public function findById(int $id): ?County
    {
        return $this->countyRepository->find($id);
    }

    public function create(string $county, float $taxRate, int $taxAmount, State $state)
    {
        $this->countyRepository->save(County::create($county, $taxRate, $taxAmount, $state), $state);
    }
}