<?php


namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * Class County
 *
 * @package App\Entities
 *
 * @ORM\Entity()
 * @ORM\Table(name="counties")
 */
class County
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
     * @ORM\Column(type="integer", name="tax_rate")
     */
    private $taxRate;

    /**
     * @ORM\Column(type="integer", name="tax_amount")
     */
    private $taxAmount;

    /**
     * @var State
     * @ORM\ManyToOne(targetEntity="State", inversedBy="counties")
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $state;

    private const AFTER_COMMA = 2;

    private function __construct(string $name, int $taxRate, int $taxAmount, ?State $state = null, ?int $id = null) {
        if($taxRate < 1) {
            throw new DomainException('Tax rate must be more than 0');
        }
        if($taxAmount < 1) {
            throw new DomainException('Tax amount must be more than 0');
        }
        $this->id = $id;
        $this->name = $name;
        $this->state = $state;
        $this->taxRate = $taxRate;
        $this->taxAmount = $taxAmount;
    }

    /**
     * @param County $county
     * @return bool
     */
    public function isEqual(self $county): bool
    {
        return $this === $county;
    }

    public static function create(string $name, float $taxRate, int $taxAmount, ?State $state = null): County
    {
        return new self($name, $taxRate*(10**self::AFTER_COMMA), $taxAmount, $state);
    }

    public function getTaxRate(): float
    {
        return round($this->taxRate/10**self::AFTER_COMMA, 2);
    }

    public function getTaxAmount(): int
    {
        return $this->taxAmount;
    }
}