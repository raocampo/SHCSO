<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/sistema');

        $response->assertStatus(200);
    }

    public function test_the_system_nested_route_returns_successful_response(): void
    {
        $response = $this->get('/sistema/trabajadores');

        $response->assertStatus(200);
    }

    public function test_the_system_operation_route_returns_successful_response(): void
    {
        $response = $this->get('/sistema/operacion');

        $response->assertStatus(200);
    }
}
