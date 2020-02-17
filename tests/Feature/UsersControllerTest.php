<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test Index Page.
     * @test
     * @return void
     */
    public function user_can_view_index_page()
    {
        $user = factory("App\User")->create();

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200)
            ->assertViewIs("users.index")
            ->assertSeeText("Manage the Users of the Application.")
            ->assertSeeText("Add")
            ->assertSeeTextInOrder(["No", "Name", "Image", "Email", "Created", "Action"]);
    }

    /**
     * Test for users data
     *
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
                "name" => e($random_user->name),
                "email"=> $random_user->email,
            ]);
    }

    /**
     * @test
     */
    public function user_can_add_user()
    {
        $this->withExceptionHandling();
        $user = factory("App\User")->create();

        $fake_name = e($this->faker->name);
        $fake_email = $this->faker->unique()->safeEmail;
        $fake_password = $this->faker->password;

        $this->actingAs($user)
            ->post("/users",
                [
                    "name" => $fake_name,
                    "email" => $fake_email,
                    "password" => $fake_password,
                    "password_confirmation" => $fake_password,
                ])
            ->assertRedirect("/users");

        $response = $this->actingAs($user)->ajaxGet("/users");

        $response->assertStatus(200)
            ->assertJsonCount(1, "data")
            ->assertJsonFragment([
                "name" => $fake_name,
                "email"=> $fake_email,
            ]);
    }

    /**
     * Test for Deleting Users
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

    /**
     * Test for Updating User
     * @test
     */
    public function user_can_update_account()
    {
        $this->withExceptionHandling();
        $user = factory("App\User")->create();

        $fake_name = $this->faker->name;
        $fake_email = $this->faker->unique()->safeEmail;
        $fake_password = $this->faker->password;

        $this->actingAs($user)->post("/users/$user->id",
            [
                "id" => $user->id,
                "_method" => 'PUT',
                "name" => $fake_name,
                "email" => $fake_email,
                "password" => $fake_password,
                "password_confirmation" => $fake_password,
            ])
            ->assertRedirect("users/$user->id/edit");

        $response = $this->actingAs($user)->get("users/$user->id/edit");

        $response->assertSeeInOrder([$fake_name,$fake_email, "Edit Account"]);
    }
}
