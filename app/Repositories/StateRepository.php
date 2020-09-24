<?php

namespace App\Repositories;

use App\Entities\Country;
use App\Entities\State;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

class StateRepository
{
    private $em;

    private $emRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        $this->emRepository = $em->getRepository(State::class);
    }

    public function find(int $id): ?State
    {
        $state = $this->emRepository->find($id);

        if($state === null) {
            throw new DomainException("State not found, id: {$id}");
        }

        return $state;
    }

    /**
     * @param State $state
     */
    public function save(State $state, Country $country): void
    {
        $country->addState($state);

        $this->em->persist($state);
        $this->em->flush();
    }
}