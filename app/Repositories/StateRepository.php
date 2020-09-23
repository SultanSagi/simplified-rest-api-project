<?php

namespace App\Repositories;

use App\Entities\State;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

class StateRepository
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function find(int $id): ?State
    {
        $stateRepository = $this->em->getRepository(State::class);

        $state = $stateRepository->find($id);

        if($state === null) {
            throw new DomainException("Product not found, id: {$id}");
        }

        return $state;
    }
}