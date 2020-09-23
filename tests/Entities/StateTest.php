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
    public function testGetOverallTaxAmount()
    {
        $state = new State('State');

        $county = County::create('County One',0.01, 150);
        $state->addCounty($county);
        $this->assertSame(150, $state->getOverallAmount());
        $this->assertSame($county->getTaxAmount(), $state->getOverallAmount());

        $countyTwo = County::create('County Two',0.01, 340);
        $state->addCounty($countyTwo);
        $this->assertSame(490, $state->getOverallAmount());
        $this->assertSame(
            $county->getTaxAmount() + $countyTwo->getTaxAmount(),
            $state->getOverallAmount()
        );
    }

    /**
     * Output the average amount of taxes collected per state
     */
    public function testGetAverageTaxAmount()
    {
        $state = new State('State');

        $county = County::create('County One',0.01, 150);
        $state->addCounty($county);
        $this->assertSame(150, $state->getAverageAmount());
        $this->assertSame($county->getTaxAmount(), $state->getAverageAmount());

        $countyTwo = County::create('County Two',0.01, 340);
        $state->addCounty($countyTwo);
        $this->assertSame(245, $state->getAverageAmount());
        $this->assertSame(
            ($county->getTaxAmount() + $countyTwo->getTaxAmount())/$state->getCountiesCount(),
            $state->getAverageAmount()
        );
    }

    /**
     * Output the average county tax rate per state
     */
    public function testGetAverageTaxRate()
    {
        $state = new State('State');

        $county = County::create('County One',0.5, 150);
        $state->addCounty($county);
        $this->assertSame(0.5, $state->getAverageRate());
        $this->assertSame($county->getTaxRate(), $state->getAverageRate());

        $countyTwo = County::create('County Two',0.03, 340);
        $state->addCounty($countyTwo);
        $this->assertSame(0.265, $state->getAverageRate());
        $this->assertSame(
            ($county->getTaxRate() + $countyTwo->getTaxRate())/$state->getCountiesCount(),
            $state->getAverageRate()
        );
    }
}