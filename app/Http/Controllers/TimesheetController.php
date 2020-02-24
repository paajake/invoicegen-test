<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\StoreTimesheet;
use App\Imports\TimesheetsImport;
use App\Lawyer;
use App\Timesheet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class TimesheetController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return View
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->timeSheetsDataTable();
        }
        return view("timesheets.index");
    }

    /**
     * Returns Datatable for Users.
     * @throws \Exception
     */
    private function timeSheetsDataTable()
    {
        DB::statement(DB::raw('set @rownum=0'));

        $data = Timesheet::join('lawyers', 'timesheets.lawyer_id', '=', 'lawyers.id')
            ->leftJoin('titles', 'lawyers.title_id', '=', 'titles.id')
            ->join('clients', 'timesheets.client_id', '=', 'clients.id')
            ->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'timesheets.id',
                DB::raw("CONCAT_WS(' ',lawyers.first_name,lawyers.last_name,titles.title ) as lawyer"),
                'clients.name as client',
                'start_time',
                'end_time',
                'timesheets.updated_at'
            ]);

        return Datatables::of($data)
            ->addColumn('day', function ($row) {
                return date('d/m/y', strtotime($row->start_time) );;
            })
            ->editColumn('start_time', function ($row) {
                return date('H:i', strtotime($row->start_time) );
            })
            ->editColumn('end_time', function ($row) {
                return date('H:i', strtotime($row->end_time) );
            })
            ->editColumn('updated_at', function ($lawyer) {
                return date('d/m/y H:i', strtotime($lawyer->updated_at) );
            })
            ->filterColumn('day', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(start_time,'%d/%m/%y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('start_time', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(start_time,'%H:%i') like ?", ["%$keyword%"]);
            })
            ->filterColumn('end_time', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(end_time,'%H:%i') like ?", ["%$keyword%"]);
            })
            ->filterColumn('updated_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(lawyers.updated_at,'%d/%m/%y %H:%i') like ?", ["%$keyword%"]);
            })
            ->filterColumn('lawyer', function ($query, $keyword) {
                $query->whereRaw("CONCAT(first_name,' ',last_name,' ', titles.title) like ?", ["%$keyword%"]);
            })
            ->filterColumn('client', function ($query, $keyword) {
                $query->whereRaw("clients.name like ?", ["%$keyword%"]);
            })
            ->addColumn('action', function($row){
                return "<a href='".route("timesheets.edit",["timesheet" => $row->id])."'
                        class='btn btn-success btn-sm mr-1 mb-1' data-toggle='tooltip' title='Edit TimeSheet'>
                        <i class='fas fa-edit'></i> <span class='d-none d-md-inline'>Edit</span></a>

                        <button type='button' id='row_$row->id' onclick='deleteTimeSheet($row->id, this.id)'
                        class='btn btn-danger btn-sm' data-toggle='tooltip' title='Delete TimeSheet'>
                        <i class='fas fa-trash'></i> <span class='d-none d-md-inline'>Delete</span></button>";
            })
            ->rawColumns(["action"])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $lawyers = Lawyer::all();
        $clients = Client::all();

        return view("timesheets.create", compact("lawyers", "clients"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTimesheet $request
     * @return RedirectResponse
     */
    public function store(StoreTimesheet $request)
    {
        Timesheet::create($request->validated());

        Cache::increment('timesheets_count');

        return redirect(route('timesheets.index'))
            ->withSuccess("TimeSheet Successfully Added!");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Timesheet  $timesheet
     * @return View
     */
    public function edit(Timesheet $timesheet)
    {
        $lawyers = Lawyer::all();
        $clients = Client::all();

        return view("timesheets.edit", compact("timesheet", "lawyers", "clients"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreTimesheet $request
     * @param Timesheet $timesheet
     * @return RedirectResponse
     */
    public function update(StoreTimesheet $request, Timesheet $timesheet)
    {
        $timesheet->update($request->validated());

        return redirect(route('timesheets.index'))
            ->withSuccess("TimeSheet Successfully Updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Timesheet $timesheet
     * @return bool|int
     * @throws \Exception
     */
    public function destroy(Timesheet $timesheet)
    {
        $timesheet->delete();

        return Cache::decrement('timesheets_count');
    }

    public function import(Request $request)
    {
        $request->validate([
            "timesheet" => "required|mimes:csv,txt",
        ]);

        Excel::import(new TimesheetsImport, $request->file("timesheet"));

        return redirect(route("timesheets.index"))
            ->with("success", "TimeSheet Uploaded Successfully");
    }

    public function upload()
    {
        return view("timesheets.upload");
    }
}
