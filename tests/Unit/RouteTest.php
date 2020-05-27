<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->json('GET', '/v1/get-foot-print', ['activity' => '1','activityType' => 'miles','mode'=>"dieselCar","country_code"=>"GBR"]);

        $response->assertStatus(200);
    }
}
