<?php


namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use DomainException;
use Exception;

/**
 * Class Country
 *
 * @package App\Entities
 *
 * @ORM\Entity()
 * @ORM\Table(name="countries")
 */
class Country
{
    public const STATE_LIMIT = 5;

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
     * @var State[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="State", mappedBy="country", orphanRemoval=true, cascade={"persist"})
     */
    private $states;

    /**
     * Country constructor.
     * @param string $name
     * @param int|null $id
     */
    public function __construct(string $name, ?int $id = null)
    {
        $this->name = $name;
        $this->id = $id;
        $this->states = new ArrayCollection();
    }

    public function addState(State $state)
    {
        $this->guardAgainstTooManyStates();
        $this->guardAgainstDuplication($state);

        $this->states->add($state);
    }

    /**
     * @return State[]
     */
    public function getStates(): array
    {
        return $this->states->toArray();
    }

    /**
     * @return int
     */
    public function getStatesCount(): int
    {
        return $this->states->count();
    }

    /**
     * Get average tax rate
     *
     * @return int
     */
    public function getAverageRate(): float
    {
        $sum = 0;

        foreach ($this->getStates() as $county) {
            $sum += $county->getAverageRate();
        }

        return $sum > 0 ? round(($sum / $this->getStatesCount()), 2) : 0;
    }

    /**
     * @param State $state
     */
    protected function guardAgainstDuplication(State $state)
    {
        foreach ($this->getStates() as $existing) {
            if($existing->isEqual($state)) {
                throw new DomainException("County is already added.");
            }
        }
    }

    /**
     * @throws Exception
     */
    protected function guardAgainstTooManyStates()
    {
        if($this->states->count() >= self::STATE_LIMIT) {
            throw new Exception("State limit exceeded.");
        }
    }
}