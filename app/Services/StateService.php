<?php

namespace App\Services;

use App\Entities\Country;
use App\Entities\State;
use App\Repositories\StateRepository;

class StateService
{
    private $stateRepository;

    public function __construct(StateRepository $stateRepository)
    {
        $this->stateRepository = $stateRepository;
    }

    /**
     * Fetch state by id
     *
     * @param int $id
     * @return State|null
     */
    public function findById(int $id): ?State
    {
        return $this->stateRepository->find($id);
    }

    public function create(string $state, Country $country)
    {
        $this->stateRepository->save(new State($state, $country), $country);
    }
}