<?php


namespace Tests\Entities;


use App\Entities\County;
use App\Entities\State;
use PHPUnit\Framework\TestCase;

class StateTest extends TestCase
{
    /**
     * States are divided into counties
     */
    public function testHaveCounties()
    {
        $state = new State('State');

        $county = County::create('County One',0.01, 150);
        $countyTwo = County::create('County Two',0.01, 340);

        $state->addCounty($county);
        $state->addCounty($countyTwo);

        $this->assertCount(2, $state->getCounties());
    }

    /**
     * Output the overall amount of taxes collected per state
     */
    public function testGetOverallAmountOfTaxesPerState()
    {
        $state = new State('State');

        $county = County::create('County One',0.01, 150);
        $state->addCounty($county);
        $this->assertSame(150, $state->getOverallAmount());

        $countyTwo = County::create('County Two',0.01, 340);
        $state->addCounty($countyTwo);
        $this->assertSame(490, $state->getOverallAmount());
    }
}