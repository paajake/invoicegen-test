<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $data["total_hours"] = Cache::remember('total_hours', config('constants.time.half_day'),
                                                function (){
                                                    return $this->get_total_billable_hours();
                                                });

        $data["total_revenue"] = Cache::remember('total_revenue', config('constants.time.half_day'),
                                                function (){
                                                    return $this->get_total_revenue();
                                                });

        return view("dashboard", $data);
    }

    private function get_total_billable_hours()
    {
        return DB::Select("SELECT  SUM(TIMESTAMPDIFF(HOUR, timesheets.start_time, timesheets.end_time)) as total_hours
                                  FROM timesheets
                                ")[0]->total_hours;
    }

    private function get_total_revenue()
    {
        $billables = DB::select("SELECT timesheets.lawyer_id,
                                       CONCAT_WS(' ',titles.title, lawyers.first_name, lawyers.last_name) as lawyer,
                                           SUM(TIMESTAMPDIFF(HOUR, timesheets.start_time, timesheets.end_time)) as hours,
                                           ranks.rate AS rank_rate,
                                           lawyers.addon_rate,
                                            ranks.rate + (ranks.rate * lawyers.addon_rate * 0.01) as unit_rate,
                                            (ranks.rate + (ranks.rate * lawyers.addon_rate * 0.01))  * SUM(TIMESTAMPDIFF(HOUR, 							timesheets.start_time, timesheets.end_time)) as total_rate

                                        FROM timesheets
                                        JOIN lawyers
                                            ON timesheets.lawyer_id = lawyers.id
                                        JOIN titles
                                            ON titles.id = lawyers.title_id
                                        JOIN ranks
                                            ON ranks.id = lawyers.rank_id
                                        GROUP BY timesheets.lawyer_id
                                        ");

        $total_revenue = 0;
        foreach ($billables as $billable){
            $total_revenue += $billable->total_rate;
        }
        return $total_revenue;
    }
}
