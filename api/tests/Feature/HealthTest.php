<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HealthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_the_health_check_endpoint_returns_success()
    {
        $response = $this->get('/api/health');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Service healthy',
            ]);
    }
}
