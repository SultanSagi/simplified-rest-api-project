<?php


namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

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

    private $counties = [];

    public function __construct(string $name, ?int $id = null)
    {
        $this->name = $name;
        $this->id = $id;
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

    public function addCounty(County $county)
    {
        $this->counties[] = $county;
    }

    public function getOverallAmount(): int
    {
        return array_reduce($this->counties, function($overall, $county) {
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