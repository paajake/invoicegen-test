<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test Index Page.
     * @test
     * @watch
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
            ->assertSeeTextInOrder(["No", "Name", "Image", "Email", "Updated", "Action"]);
    }

    /**
     * Test for users data
     *
     * @test
     * @watch
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
                "email" => $random_user->email,
            ]);
    }

    /**
     * @test
     * @watch
     * @return void
     */
    public function user_can_view_user_create_page()
    {
        $user = factory("App\User")->create();

        $response = $this->actingAs($user)->get('/users/create');

        $response->assertStatus(200)
            ->assertViewIs("users.create")
            ->assertSeeTextInOrder([
                "Users",
                "Add New User",
                "Browse",
                "Add User",
            ]);
    }

    /**
     * @test
     * @watch
     */
    public function user_can_add_user()
    {
        $user = factory("App\User")->create();

        $fake_name = e($this->faker->name);
        $fake_email = e($this->faker->unique()->safeEmail);
        $fake_password = e($this->faker->password(8));

        $this->actingAs($user)
            ->postJson("/users",
                [
                    "name" => $fake_name,
                    "email" => $fake_email,
                    'image' => UploadedFile::fake()->image("image.png"),
                    "password" => $fake_password,
                    "password_confirmation" => $fake_password,
                ])
            ->assertRedirect("/users")
            ->assertSessionHas("success", "User Successfully Created!");


        $response = $this->actingAs($user)->ajaxGet("/users");

        $response->assertStatus(200)
            ->assertJsonCount(1, "data")
            ->assertJsonFragment([
                "name" => e($fake_name),
                "email"=> e($fake_email),
            ]);

        $fake_user_id = $response->decodeResponseJson("data")[0]["id"];
        $image_name = User::find($fake_user_id)->image;

        Storage::assertExists("public/images/uploads/$image_name");
    }

    /**
     * Test for Deleting Users
     * @test
     * @watch
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
                "name" => e($random_user->name),
                "email" => e($random_user->email),
            ]);
    }

    /**
     * Test for Updating User
     * @test
     * @watch
     */
    public function user_can_update_account()
    {
        $user = factory("App\User")->create();

        $fake_name = e($this->faker->name);
        $fake_email = e($this->faker->unique()->safeEmail);
        $fake_password = e($this->faker->password(8));

        $this->actingAs($user)->post("/users/$user->id",
            [
                "id" => $user->id,
                "_method" => 'PUT',
                "name" => $fake_name,
                "email" => $fake_email,
                "password" => $fake_password,
                "password_confirmation" => $fake_password,
            ])
            ->assertRedirect("users/$user->id/edit")
            ->assertSessionHas("success", "Account Successfully Updated!");

        $response = $this->actingAs($user)->get("users/$user->id/edit");

        $response->assertSeeInOrder([e($fake_name), e($fake_email), "Edit Account"]);
    }
}
