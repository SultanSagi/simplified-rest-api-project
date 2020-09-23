<?php


namespace Tests\Entities;


use App\Entities\Country;
use App\Entities\County;
use App\Entities\State;
use PHPUnit\Framework\TestCase;
use Exception;

class CountryTest extends TestCase
{
    /**
     * Country has many states
     */
    public function testHaveStates()
    {
        $country = new Country('Country');

        $state = new State('State One');
        $stateTwo = new State('State Two');

        $country->addState($state);
        $country->addState($stateTwo);

        $this->assertCount(2, $country->getStates());
    }

    /**
     * The country is organized in 5 states
     */
    public function testLimitedStateSize()
    {
        $country = new Country('Country');

        $state = new State('State One');
        $stateTwo = new State('State Two');
        $stateThree = new State('State Three');
        $stateFour = new State('State Four');
        $stateFive = new State('State Five');
        $stateSix = new State('State Six');

        $country->addState($state);
        $country->addState($stateTwo);
        $country->addState($stateThree);
        $country->addState($stateFour);
        $country->addState($stateSix);

        $this->expectException(Exception::class);
        $country->addState($stateFive);
    }

    /**
     * Output the average tax rate of the country
     */
    public function testGetAverageTaxRate()
    {
        $country = new Country('Country');

        $state = new State('State');
        $county = County::create('County One',0.05, 150);
        $countyTwo = County::create('County Two',0.21, 150);
        $countyThree = County::create('County Three',0.28, 150);
        $state->addCounty($county);
        $state->addCounty($countyTwo);
        $state->addCounty($countyThree);

        $stateTwo = new State('State Two');
        $countyFour = County::create('County One',1.54, 150);
        $countyFive = County::create('County Two',2.32, 150);
        $countySix = County::create('County Three',3.54, 150);
        $stateTwo->addCounty($countyFour);
        $stateTwo->addCounty($countyFive);
        $stateTwo->addCounty($countySix);

        $country->addState($state);
        $country->addState($stateTwo);

        $this->assertSame(1.32, $country->getAverageRate());
        $this->assertSame(
            round(($state->getAverageRate() + $stateTwo->getAverageRate())/$country->getStatesCount(),2),
            $country->getAverageRate()
        );
    }
}