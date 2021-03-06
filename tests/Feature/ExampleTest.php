<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     * @test
     * @watch
     *
     * @return void
     */
    public function guest_cant_access_dashboard()
    {
        $response = $this->get('/');
        $response->assertRedirect("/login");
    }

    /**
     * A basic test example.
     * @test
     * @watch
     *
     * @return void
     */
    public function guest_cant_register_thyself()
    {
        $response = $this->get('/register');
        $response->assertStatus(404);
    }
}
