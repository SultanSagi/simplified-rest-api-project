<?php

namespace App\Repositories;

use App\Entities\Country;
use App\Entities\State;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

class CountryRepository
{
    private $em;

    private $emRepository;

    /**
     * CountryRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        $this->emRepository = $em->getRepository(Country::class);
    }

    /**
     * @param int $id
     * @return State|null
     */
    public function find(int $id): ?Country
    {
        $country = $this->emRepository->find($id);

        if($country === null) {
            throw new DomainException("Country not found, id: {$id}");
        }

        return $country;
    }

    /**
     * @param Country $country
     */
    public function save(Country $country): void
    {
        $this->em->persist($country);
        $this->em->flush();
    }
}