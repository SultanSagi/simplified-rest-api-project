<?php


namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
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
     * @var ArrayCollection|State[]
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
        $stateCount = 0;

        foreach ($this->getStates() as $state) {
            $sum += $state->getAverageRate();
            $stateCount += ($state->getAverageRate() > 0) ? 1 : 0;
        }

        return $sum > 0 ? round(($sum / $stateCount), 2) : 0;
    }

    /**
     * Get collected overall amount
     *
     * @return int
     */
    public function getOverallAmount(): int
    {
        return array_reduce($this->getStates(), function($overall, $state) {
            return $overall + $state->getOverallAmount();
        });
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

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'average_rate' => $this->getAverageRate(),
            'overall_amount' => $this->getOverallAmount(),
        ];
    }
}