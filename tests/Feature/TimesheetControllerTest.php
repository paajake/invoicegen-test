<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TimesheetControllerTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;
    /**
     * Test Lawyers Page.
     * @test
     * @return void
     */
    public function user_can_view_timesheets()
    {
        $user = factory("App\User")->create();

        $response = $this->actingAs($user)->get('/timesheets');

        $response->assertStatus(200)
            ->assertViewIs("timesheets.index")
            ->assertSeeText("Manage Lawyers' TimeSheets")
            ->assertSeeText("Add")
            ->assertSeeTextInOrder([
                "No",
                "Lawyer",
                "Client",
                "Day",
                "Start",
                "End",
                "Updated",
                "Action",
            ]);
    }

    /**
     * @test
     */
    public function user_can_access_timesheets_data()
    {
        $ranks = factory("App\Rank", 6)->create();
        $titles = factory("App\Title", 5)->create();
        $clients = factory("App\Client", 23)->create();
        $lawyers = factory("App\Lawyer", 12)->create();

        $random_timesheet = factory("App\Timesheet", 9)->create()->random();

        $client = $clients->find($random_timesheet->client_id);
        $lawyer = $lawyers->find($random_timesheet->lawyer_id);
        $lawyer_title = $titles->find($lawyer->title_id)->title;
        $lawyer_name = $lawyer->first_name. ' '. $lawyer->last_name. ' '. $lawyer_title;

        $user = factory("App\User")->create();
        $response = $this->actingAs($user)->ajaxGet("/timesheets");

        $response->assertStatus(200)
            ->assertJsonCount(9, "data")
            ->assertJsonFragment([
                "lawyer" => e($lawyer_name),
                "client"=> e($client->name),
                "day"=> e($random_timesheet->start_time->format("d/m/y")),
                "start_time"=> e($random_timesheet->start_time->format("H:i")),
                "end_time"=> e($random_timesheet->end_time->format("H:i")),

            ]);
    }

    /**
     * @test
     * @return void
     */
    public function user_can_view_timesheets_create_page()
    {
        $user = factory("App\User")->create();

        $response = $this->actingAs($user)->get('/timesheets/create');

        $response->assertStatus(200)
            ->assertViewIs("timesheets.create")
            ->assertViewHas(["lawyers", "clients"])
            ->assertSeeTextInOrder([
                "Add New TimeSheet",
                "Add TimeSheet",
            ]);
    }

    /**
     * @test
     */
    public function user_can_add_timesheet(){
        $ranks = factory("App\Rank", 6)->create();
        $titles = factory("App\Title", 5)->create();
        $lawyers = factory("App\Lawyer", 12)->create();
        $clients = factory("App\Client", 23)->create();

        $random_client = $clients->random();
        $random_lawyer = $lawyers->random();
        $lawyer = $random_lawyer->first_name.' '.$random_lawyer->last_name.' '. $random_lawyer->title()->first()->title;
        $start_time = Carbon::today()->addHours($this->faker->numberBetween(6, 12));
//        $end_time = $start_time->addHours($this->faker->numberBetween(3,12));
        $end_time = Carbon::tomorrow()->addHours(-5);

        $user = factory("App\User")->create();
        $this->actingAs($user)
            ->postJson("/timesheets",
                [
                    "lawyer_id" => $random_lawyer->id,
                    "client_id" => $random_client->id,
                    "start_time" => $start_time->toDateTimeString(),
                    "end_time" => $end_time->toDateTimeString(),
                ])
            ->assertRedirect("timesheets")
            ->assertSessionHas("success","TimeSheet Successfully Added!");

        $response = $this->actingAs($user)->ajaxGet("/timesheets");

        $response->assertStatus(200)
            ->assertJsonCount(1, "data")
            ->assertJsonFragment([
                "lawyer" => e($lawyer),
                "client" => e($random_client->name),
                "day" => $start_time->format("d/m/y"),
                "start_time" => $start_time->format("H:i"),
                "end_time" => $end_time->format("H:i"),
            ]);

    }

    /**
     * @test
     */
    public function user_can_delete_timesheet(){
        $ranks = factory("App\Rank", 6)->create();
        $titles = factory("App\Title", 5)->create();
        $lawyers = factory("App\Lawyer", 12)->create();
        $clients = factory("App\Client", 23)->create();

        $random_timesheet = factory("App\Timesheet", 3)->create()->random();

        $random_client = $clients->find($random_timesheet->client_id);
        $random_lawyer = $lawyers->find($random_timesheet->lawyer_id);
        $lawyer = $random_lawyer->first_name.' '.$random_lawyer->last_name.' '. $random_lawyer->title()->first()->title;

        $user = factory("App\User")->create();

        $this->actingAs($user)
            ->postJson("/timesheets/$random_timesheet->id", ["id" => $random_timesheet->id, "_method" => 'DELETE'] )
            ->assertStatus(200);

        $response = $this->actingAs($user)->ajaxGet("/timesheets");

        $response->assertStatus(200)
            ->assertJsonCount(2, "data")
            ->assertJsonMissingExact([
                "lawyer" => e($lawyer),
                "client" => e($random_client->name),
                "day" => $random_timesheet->start_time->format("d/m/y"),
                "start_time" => $random_timesheet->start_time->format("H:i"),
                "end_time" => $random_timesheet->end_time->format("H:i"),
            ]);

    }

    /**
     * @test
     */
    public function user_can_update_timesheet(){
        $ranks = factory("App\Rank", 6)->create();
        $titles = factory("App\Title", 5)->create();
        $lawyers = factory("App\Lawyer", 12)->create();
        $clients = factory("App\Client", 23)->create();

        $random_timesheet = factory("App\Timesheet", 3)->create()->random();

        $random_client = $clients->random();
        $random_lawyer = $lawyers->random();
        $lawyer = $random_lawyer->first_name.' '.$random_lawyer->last_name.' '. $random_lawyer->title()->first()->title;
        $start_time = Carbon::today()->addHours($this->faker->numberBetween(6, 12));
        $end_time = Carbon::tomorrow()->addHours(-5);

        $user = factory("App\User")->create();

        $this->actingAs($user)->postJson("/timesheets/$random_timesheet->id",
            [
                "id" => $random_timesheet->id,
                "_method" => 'PUT',
                "client_id" => $random_client->id,
                "lawyer_id" => $random_lawyer->id,
                "start_time" => $start_time->toDateTimeString(),
                "end_time" => $end_time->toDateTimeString(),
            ])
            ->assertRedirect("/timesheets")
            ->assertSessionHas("success","TimeSheet Successfully Updated!");

        $response = $this->actingAs($user)->get("timesheets/$random_timesheet->id/edit");

        $response->assertSeeInOrder([
            e($lawyer),
            e($random_client->name),
            $start_time->format("Y-m-d H:i"),
            $end_time->format("Y-m-d H:i"),
            "Edit TimeSheet"
        ]);
    }

}
