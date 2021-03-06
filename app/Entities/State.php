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
     * @var Country
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="states")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $country;

    /**
     * @var County[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="County", mappedBy="state", orphanRemoval=true, cascade={"persist"})
     */
    private $counties;

    public function __construct(string $name, ?Country $country = null, ?int $id = null)
    {
        $this->name = $name;
        $this->id = $id;
        $this->country = $country;
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

    /**
     * @return int
     */
    public function getCountiesCount(): int
    {
        return $this->counties->count();
    }

    /**
     * Get overall amount of counties
     *
     * @return int
     */
    public function getOverallAmount(): int
    {
        return $this->getCountiesCount() > 0 ? array_reduce($this->getCounties(), function($overall, $county) {
            return $overall + $county->getTaxAmount();
        }) : 0;
    }

    /**
     * Get average amount of counties
     *
     * @return int
     */
    public function getAverageAmount(): int
    {
        $sum = $this->getOverallAmount();

        return $sum > 0 ? ($sum / $this->getCountiesCount()) : 0;
    }

    /**
     * Get average tax rate of counties
     *
     * @return int
     */
    public function getAverageRate(): float
    {
        $sum = 0;

        foreach ($this->getCounties() as $county) {
            $sum += $county->getTaxRate();
        }

        return $sum > 0 ? ($sum / $this->getCountiesCount()) : 0;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'overall_amount' => $this->getOverallAmount(),
            'average_amount' => $this->getAverageAmount(),
            'average_rate' => $this->getAverageRate(),
        ];
    }
}