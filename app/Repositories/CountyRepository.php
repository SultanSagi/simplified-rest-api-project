<?php

namespace App\Repositories;

use App\Entities\Country;
use App\Entities\County;
use App\Entities\State;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

class CountyRepository
{
    private $em;

    private $emRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        $this->emRepository = $em->getRepository(County::class);
    }

    public function find(int $id): ?County
    {
        $county = $this->emRepository->find($id);

        if($county === null) {
            throw new DomainException("County not found, id: {$id}");
        }

        return $county;
    }

    /**
     * @param County $county
     * @param State $state
     */
    public function save(County $county, State $state): void
    {
        $state->addCounty($county);

        $this->em->persist($county);
        $this->em->flush();
    }
}