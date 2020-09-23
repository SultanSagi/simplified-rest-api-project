<?php


namespace Tests\Entities;


use App\Entities\County;
use App\Entities\State;
use PHPUnit\Framework\TestCase;

class StateTest extends TestCase
{
    /**
     * Output the overall amount of taxes collected per state
     */
    public function testGetOverallAmountOfTaxesPerState()
    {
        $state = new State('State');

        $county = County::create('County One',0.01, 150);
        $state->addCounty($county);
        $this->assertSame(150, $state->getOverallAmount());

        $county2 = County::create('County Two',0.01, 340);
        $state->addCounty($county2);
        $this->assertSame(490, $state->getOverallAmount());
    }
}