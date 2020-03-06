<?php

namespace Tests\Feature;

use App\Lawyer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LawyerControllerTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    /**
     * Test Lawyers Page.
     * @test
     * @watch
     * @return void
     */
    public function user_can_view_lawyers()
    {
        $user = factory("App\User")->create();

        $response = $this->actingAs($user)->get('/lawyers');

        $response->assertStatus(200)
            ->assertViewIs("lawyers.index")
            ->assertSeeText("Manage Lawyers")
            ->assertSeeText("Add")
            ->assertSeeTextInOrder([
                "ID",
                "Name",
                "Image",
                "Rank",
                "Email",
                "Phone",
                "Addon",
                "Update",
                "Action",
            ]);

    }

    /**
     * @test
     * @watch
     */
    public function user_can_access_lawyers_data()
    {
        $ranks = factory("App\Rank", 6)->create();
        $titles = factory("App\Title", 6)->create();

        $random_lawyer = factory("App\Lawyer", 6)->create()->random();

        $lawyer_rank = $ranks->find($random_lawyer->rank_id)->name;
        $lawyer_title = $titles->find($random_lawyer->title_id)->title;
        $lawyer_name = e($random_lawyer->first_name. ' '. $random_lawyer->last_name. ' '. $lawyer_title);

        $user = factory("App\User")->create();
        $response = $this->actingAs($user)->ajaxGet("/lawyers");

        $response->assertStatus(200)
            ->assertJsonCount(6, "data")
            ->assertJsonFragment([
                "name" => $lawyer_name,
                "rank"=> e($lawyer_rank),
                "email"=> "<a href='mailto:$random_lawyer->email'>$random_lawyer->email</a>",
                "phone"=> "<a href='tel:$random_lawyer->phone'>$random_lawyer->phone</a>",
                "addon_rate"=> number_format($random_lawyer->addon_rate, 2, '.', ""),
            ]);
    }

    /**
     * @test
     * @watch
     * @return void
     */
    public function user_can_view_lawyers_create_page()
    {
        $user = factory("App\User")->create();

        $response = $this->actingAs($user)->get('/lawyers/create');

        $response->assertStatus(200)
            ->assertViewIs("lawyers.create")
            ->assertViewHas(["titles", "ranks"])
            ->assertSeeTextInOrder([
                "Lawyers",
                "Add New Lawyer",
                "Browse",
                "Add Lawyer",
            ]);
    }

    /**
     * @test
     * @watch
     */
    public function user_can_add_lawyer(){
        $ranks = factory("App\Rank", 6)->create();
        $titles = factory("App\Title", 6)->create();

        $random_rank = $ranks->random();
        $random_title = $titles->random();

        $fake_addon_rate = $this->faker->randomFloat(2, 0, 10);
        $fake_first_name = e($this->faker->firstName);
        $fake_last_name = e($this->faker->lastName);
        $fake_name = e($fake_first_name. ' '.$fake_last_name. ' '. $titles->find($random_title->id)->title);

        $fake_email = e($this->faker->companyEmail);
        $fake_phone = $this->faker->e164PhoneNumber;

        $user = factory("App\User")->create();
        $this->actingAs($user)
            ->post("/lawyers",
                [
                    "rank_id" => $random_rank->id,
                    "title_id" => $random_title->id,
                    "first_name" => $fake_first_name,
                    "last_name" => $fake_last_name,
                    "phone" => $fake_phone,
                    "email" => $fake_email,
                    "addon_rate" => $fake_addon_rate,
                    'image' => UploadedFile::fake()->image("image.png"),
                ])
            ->assertRedirect("lawyers")
            ->assertSessionHas("success","Lawyer Successfully Added!");

        $response = $this->actingAs($user)->ajaxGet("/lawyers");

        $response->assertStatus(200)
            ->assertJsonCount(1, "data")
            ->assertJsonFragment([
                "name" => $fake_name,
                "rank" => $random_rank->name,
                "email"=> "<a href='mailto:$fake_email'>$fake_email</a>",
                "phone"=> "<a href='tel:$fake_phone'>$fake_phone</a>",
                "addon_rate" => number_format($fake_addon_rate, 2, '.', ""),
            ]);

        $fake_lawyer_id = $response->decodeResponseJson("data")[0]["id"];
        $image_name = Lawyer::find($fake_lawyer_id)->image;

        Storage::assertExists("public/images/uploads/$image_name");
    }

    /**
     * @test
     * @watch
     */
    public function user_can_delete_lawyer(){
        $ranks = factory("App\Rank", 6)->create();
        $titles = factory("App\Title", 6)->create();

        $random_lawyer = factory("App\Lawyer", 3)->create()->random();
        $random_lawyer_name = $random_lawyer->first_name. ' '.$random_lawyer->last_name. ' '. $titles->find($random_lawyer->title_id)->title;
        $random_lawyer_rank = $ranks->find($random_lawyer->rank_id)->name;

        $user = factory("App\User")->create();

        //Update Lawyer with Image so that Image Deleting too can be Tested
        $this->actingAs($user)->postJson("/lawyers/$random_lawyer->id",
            [
                "id" => $random_lawyer->id,
                "_method" => 'PUT',
                "rank_id" => $random_lawyer->rank_id,
                "title_id" => $random_lawyer->title_id,
                "first_name" => $random_lawyer->first_name,
                "last_name" => $random_lawyer->last_name,
                "phone" => $random_lawyer->phone,
                "email" => $random_lawyer->email,
                "addon_rate" => $random_lawyer->addon_rate,
                "image" => UploadedFile::fake()->image("image.png"),
            ])
            ->assertRedirect("lawyers/")
            ->assertSessionHas("success","Lawyer Successfully Updated!");

        $image_name = Lawyer::find($random_lawyer->id)->image;

        $this->actingAs($user)
            ->postJson("/lawyers/$random_lawyer->id", ["id" => $random_lawyer->id, "_method" => 'DELETE'] )
            ->assertStatus(200);

        $response = $this->actingAs($user)->ajaxGet("/lawyers");

        $response->assertStatus(200)
            ->assertJsonCount(2, "data")
            ->assertJsonMissingExact([
                "name" => $random_lawyer_name,
                "rank" => $random_lawyer_rank,
                "email"=> "<a href='mailto:$random_lawyer->email'>$random_lawyer->email</a>",
                "phone"=> "<a href='tel:$random_lawyer->phone'>$random_lawyer->phone</a>",
                "addon_rate" => number_format($random_lawyer->addon_rate, 2, '.', ""),
            ]);

        Storage::assertMissing("public/images/uploads/$image_name");
    }

    /**
     * @test
     * @watch
     */
    public function user_can_update_lawyer(){
        $random_rank = factory("App\Rank", 6)->create()->random();
        $random_title = factory("App\Title", 6)->create()->random();

        $random_lawyer = factory("App\Lawyer", 5)->create()->random();

        $fake_addon_rate = $this->faker->randomFloat(2, 0, 10);
        $fake_first_name = $this->faker->firstName;
        $fake_last_name = $this->faker->lastName;
        $fake_email = $this->faker->companyEmail;
        $fake_phone = $this->faker->e164PhoneNumber;

        $user = factory("App\User")->create();

        $this->actingAs($user)->postJson("/lawyers/$random_lawyer->id",
            [
                "id" => $random_lawyer->id,
                "_method" => 'PUT',
                "rank_id" => $random_rank->id,
                "title_id" => $random_title->id,
                "first_name" => $fake_first_name,
                "last_name" => $fake_last_name,
                "phone" => $fake_phone,
                "email" => $fake_email,
                "addon_rate" => $fake_addon_rate,
            ])
            ->assertRedirect("lawyers/")
            ->assertSessionHas("success","Lawyer Successfully Updated!");

        $response = $this->actingAs($user)->get("lawyers/$random_lawyer->id/edit");

        $response->assertSeeInOrder([
            e($random_title->title),
            e($fake_first_name),
            e($fake_last_name),
            e($random_rank->name),
            $fake_addon_rate,
            $fake_email,
            $fake_phone,
            "Edit Lawyer"
        ]);
    }
}
