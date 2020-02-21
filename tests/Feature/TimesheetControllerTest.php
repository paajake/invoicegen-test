<?php

namespace Tests\Feature;

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


}
