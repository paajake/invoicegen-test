<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RankControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test Ranks Page.
     * @test
     * @return void
     */
    public function user_can_view_ranks()
    {
        $user = factory("App\User")->create();

        $response = $this->actingAs($user)->get('/ranks');

        $response->assertStatus(200)
            ->assertViewIs("ranks.index")
            ->assertSeeText("Manage Lawyer Ranks")
            ->assertSeeText("Add")
            ->assertSeeTextInOrder(["No", "Rank", "Rate", "Created", "Action"]);

    }

    /**
     * @test
     */
    public function user_can_access_ranks_data(){
        $random_rank = factory("App\Rank", 8)->create()->random();

        $user = factory("App\User")->create();

        $response = $this->actingAs($user)->ajaxGet("/ranks");

        $response->assertStatus(200)
            ->assertJsonCount(8, "data")
            ->assertJsonFragment([
                "name" => $random_rank->name,
                "rate"=> "$random_rank->rate",
            ]);
    }

    /**
     * @test
     */
    public function user_can_add_rank(){
        $user = factory("App\User")->create();

        $fake_rank = $this->faker->jobTitle;
        $fake_rate = $this->faker->randomFloat(2, 100, 1000);

        $this->actingAs($user)
            ->post("/ranks",
                [
                    "name" => $fake_rank,
                    "rate" => number_format($fake_rate,2),
                ])
            ->assertRedirect("ranks");

        $response = $this->actingAs($user)->ajaxGet("/ranks");

        $response->assertStatus(200)
            ->assertJsonCount(1, "data")
            ->assertJsonFragment([
                "name" => $fake_rank,
                "rate"=> number_format($fake_rate, 2),
            ]);
    }

    /**
     * @test
     */
    public function user_can_delete_rank(){
        $random_rank = factory("App\Rank", 3)->create()->random();
        $user = factory("App\User")->create();

        $this->actingAs($user)
            ->post("/ranks/$random_rank->id", ["id" => $random_rank->id, "_method" => 'DELETE'] )
            ->assertStatus(200);

        $response = $this->actingAs($user)->ajaxGet("/ranks");

        $response->assertStatus(200)
            ->assertJsonCount(2, "data")
            ->assertJsonMissing([
                "name" => $random_rank->name,
                "rate"=> "$random_rank->rate",
            ]);
    }

    /**
     * @test
     */
    public function user_can_update_rank(){
        $user = factory("App\User")->create();

        $random_rank = factory("App\Rank", 5)->create()->random();

        $fake_rank = $this->faker->jobTitle;
        $fake_rate = $this->faker->randomFloat(2, 100, 1000);

        $this->actingAs($user)->post("/ranks/$random_rank->id",
            [
                "id" => $random_rank->id,
                "_method" => 'PUT',
                "name" => $fake_rank,
                "rate" => $fake_rate,
            ])
            ->assertRedirect("ranks/");

        $response = $this->actingAs($user)->get("ranks/$random_rank->id/edit");

        $response->assertSeeInOrder([$fake_rank,$fake_rate, "Edit Rank"]);
    }
}
