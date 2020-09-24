<?php

namespace App\Services;

use App\Entities\Country;
use App\Entities\State;
use App\Repositories\CountryRepository;

class CountryService
{
    private $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * Fetch country by id
     *
     * @param int $id
     * @return State|null
     */
    public function findById(int $id): ?Country
    {
        return $this->countryRepository->find($id);
    }

    public function create(string $country)
    {
        $this->countryRepository->save(new Country($country));
    }
}