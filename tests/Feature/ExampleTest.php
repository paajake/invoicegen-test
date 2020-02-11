<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    protected function setUp(): void {
        parent::setUp();
        //$this->withoutExceptionHandling();
    }
    /**
     * A basic test example.
     * @test
     *
     * @return void
     */
    public function guest_cant_access_dashboard()
    {
        $response = $this->get('/');
        $response->assertRedirect("/login");
    }
}
