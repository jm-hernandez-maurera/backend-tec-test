<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SecurityPriceControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testShouldSyncbySecurityTypeSuccesfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
