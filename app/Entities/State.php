<?php


namespace App\Entities;

class State
{
    private $counties = [];

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
}