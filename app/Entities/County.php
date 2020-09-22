<?php


namespace app\Entities;

use DomainException;

class County
{
    private $taxRate;

    private $taxAmount;

    private const AFTER_COMMA = 2;

    private function __construct(int $taxRate, int $taxAmount) {
        if($taxRate < 1) {
            throw new DomainException('Tax rate must be more than 0.01');
        }
        if($taxAmount < 1) {
            throw new DomainException('Tax amount must be more than 1');
        }
        $this->taxRate = $taxRate;
        $this->taxAmount = $taxAmount;
    }

    public static function create(float $taxRate, int $taxAmount): County
    {
        return new self($taxRate*(10**self::AFTER_COMMA), $taxAmount);
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