<?php


namespace Tests\Entities;


use App\Entities\Country;
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
}