<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test Ranks Page.
     * @test
     * @return void
     */
    public function user_can_view_clients()
    {
        $user = factory("App\User")->create();

        $response = $this->actingAs($user)->get('/clients');

        $response->assertStatus(200)
            ->assertViewIs("clients.index")
            ->assertSeeText("Manage Clients")
            ->assertSeeText("Add")
            ->assertSeeTextInOrder(["No", "Email", "Phone", "Updated", "Action"]);

    }

    /**
     * @test
     */
    public function user_can_access_ranks_data(){
        $random_client = factory("App\Client", 8)->create()->random();

        $user = factory("App\User")->create();

        $response = $this->actingAs($user)->ajaxGet(route("clients.index"));

        $response->assertStatus(200)
            ->assertJsonCount(8, "data")
            ->assertJsonFragment([
                "name" => e($random_client->name),
                "email"=> $random_client->email,
                "phone"=> $random_client->phone,
            ]);
    }

    /**
     * @test
     */
    public function user_can_add_client(){
        $user = factory("App\User")->create();

        $fake_name = $this->faker->name;
        $fake_phone = $this->faker->e164PhoneNumber;
        $fake_email = $this->faker->unique()->safeEmail;

        $this->actingAs($user)
            ->post("/clients",
                [
                    "name" => e($fake_name),
                    "email" => $fake_email,
                    "phone" => $fake_phone,
                ])
            ->assertRedirect("/clients")
            ->assertSessionHas("success","Client Successfully Created!");

        $response = $this->actingAs($user)->ajaxGet("/clients");

        $response->assertStatus(200)
            ->assertJsonCount(1, "data")
            ->assertJsonFragment([
                "name" => e($fake_name),
                "email" => $fake_email,
                "phone" => $fake_phone,
            ]);
    }

    /**
     * @test
     */
    public function user_can_delete_client(){
        $random_client = factory("App\Client", 3)->create()->random();
        $user = factory("App\User")->create();

        $this->actingAs($user)
            ->post("/clients/$random_client->id", ["id" => $random_client->id, "_method" => 'DELETE'] )
            ->assertStatus(200);

        $response = $this->actingAs($user)->ajaxGet("/clients");

        $response->assertStatus(200)
            ->assertJsonCount(2, "data")
            ->assertJsonMissing([
                "name" => e($random_client->name),
                "email" => $random_client->email,
                "phone" => $random_client->phone,
            ]);
    }

    /**
     * @test
     */
    public function user_can_update_client(){
        $user = factory("App\User")->create();

        $random_client = factory("App\Client", 5)->create()->random();

        $fake_name = $this->faker->name;
        $fake_phone = $this->faker->e164PhoneNumber;
        $fake_email = $this->faker->unique()->safeEmail;

        $this->actingAs($user)->post("/clients/$random_client->id",
            [
                "id" => $random_client->id,
                "_method" => 'PUT',
                "name" => e($fake_name),
                "email" => $fake_email,
                "phone" => $fake_phone,
            ])
            ->assertRedirect("/clients")
            ->assertSessionHas("success","Client Successfully Updated!");
        ;

        $response = $this->actingAs($user)->get("clients/$random_client->id/edit");

        $response->assertSeeInOrder([e($fake_name), $fake_email, $fake_phone, "Edit Client"]);
    }
}
