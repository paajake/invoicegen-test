<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function user_can_access_dashboard()
    {
        $user = factory("App\User")->create();
        $response = $this->actingAs($user)->get("/");

        $response->assertStatus(200)
            ->assertViewIs("dashboard")
            ->assertSeeText("Dashboard");
    }
}
