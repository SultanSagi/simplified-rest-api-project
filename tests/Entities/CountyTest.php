<?php


namespace Tests\Entities;


use App\Entities\County;
use PHPUnit\Framework\TestCase;
use DomainException;

class CountyTest extends TestCase
{
    /**
     * Test base create
     */
    public function testCreate()
    {
        $county = County::create('County One', 0.01, 4000);
        $this->assertSame(0.01, $county->getTaxRate());
        $this->assertSame(4000, $county->getTaxAmount());
    }

    /**
     * Test check create exception
     */
    public function testCreatedException()
    {
        $this->expectException(DomainException::class);
        County::create('County One',0, 200);
    }
}