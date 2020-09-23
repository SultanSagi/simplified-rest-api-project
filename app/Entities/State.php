<?php


namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * Class State
 *
 * @package App\Entities
 *
 * @ORM\Entity()
 * @ORM\Table(name="states")
 */
class State
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var County[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="County", mappedBy="state", orphanRemoval=true, cascade={"persist"})
     */
    private $counties;

    public function __construct(string $name, ?int $id = null)
    {
        $this->name = $name;
        $this->id = $id;
        $this->counties = new ArrayCollection();
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /** @return int */
    public function getId(): int
    {
        return $this->id;
    }

    /** @return string */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param State $state
     * @return bool
     */
    public function isEqual(self $state): bool
    {
        return $this === $state;
    }

    public function addCounty(County $county)
    {
        foreach ($this->counties as $existing) {
            if($existing->isEqual($county)) {
                throw new DomainException("County is already added.");
            }
        }
        $this->counties->add($county);
    }

    /**
     * @return County[]
     */
    public function getCounties(): array
    {
        return $this->counties->toArray();
    }

    public function getOverallAmount(): int
    {
        return array_reduce($this->getCounties(), function($overall, $county) {
            return $overall + $county->getTaxAmount();
        });
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}