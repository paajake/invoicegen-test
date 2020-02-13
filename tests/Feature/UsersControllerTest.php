<?php

namespace Tests\Feature;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Index Page.
     * @test
     * @return void
     */
    public function user_can_view_index_page()
    {
        $this->withExceptionHandling();
        $user = factory("App\User")->create();

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200)
            ->assertViewIs("users.index")
            ->assertSeeText("Manage the Users of the Application.")
            ->assertSeeText("Add")
            ->assertSeeTextInOrder(["No", "Name", "Image", "Email", "Created", "Action"]);
    }

    /**
     * @test
     */
    public function user_can_access_users_data()
    {
        $random_user = factory("App\User", 8)->create()->random();
        $user = factory("App\User")->create();

        $response = $this->actingAs($user)->ajaxGet("/users");

        $response->assertStatus(200)
            ->assertJsonCount(8, "data")
            ->assertJsonFragment([
                "name" => $random_user->name,
                "email"=> $random_user->email,
            ]);


    }

    /**
     * @test
     */
    public function user_can_add_user()
    {
        $user = factory("App\User")->create();
        $faker = Factory::create();
        $fake_name = $faker->name;
        $fake_email = $faker->email;
        $fake_password = $faker->password;

        $this->actingAs($user)
            ->post("/users",
                [
                    "name" => $fake_name,
                    "email" => $fake_email,
                    "password" => $fake_password,
                    "password_confirmation" => $fake_password,
                ])
            ->assertRedirect("users");

        $response = $this->actingAs($user)->ajaxGet("/users");

        $response->assertStatus(200)
            ->assertJsonCount(1, "data")
            ->assertJsonFragment([
                "name" => $fake_name,
                "email"=> $fake_email,
            ]);
    }

    /**
     * @test
     */
    public function user_can_delete_users()
    {
        $random_user = factory("App\User", 3)->create()->random();
        $user = factory("App\User")->create();

        $this->actingAs($user)
            ->post("/users/$random_user->id", ["id" => $user->id, "_method" => 'DELETE'] )
            ->assertStatus(200);

        $response = $this->actingAs($user)->ajaxGet("/users");

        $response->assertStatus(200)
            ->assertJsonCount(2, "data")
            ->assertJsonMissing([
                "name" => $random_user->name,
                "email" => $random_user->email,
            ]);
    }
}
