<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLawyer;
use App\Lawyer;
use App\Rank;
use App\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class LawyerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->lawyersDataTable();
        }
        return view("lawyers.index");
    }

    /**
     * Returns Datatable for Users.
     */
    private function lawyersDataTable()
    {
        DB::statement(DB::raw('set @rownum=0'));

        $data = Lawyer::select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            DB::raw("CONCAT(lawyers.first_name,' ',lawyers.last_name) as name"),
            'title_id',
            'image',
            'rank_id',
            'email',
            'phone',
            'addon_rate',
            'updated_at'
        ]);

        return Datatables::of($data)
            ->editColumn('image', function ($lawyer) {
                $url = Storage::url("public/images/uploads/".$lawyer->image);
                return '<img src='.$url.' border="0" width="40" class="img-rounded m-auto"/>';
            })
            ->editColumn('updated_at', function ($lawyer) {
                return date('d/m/y H:i', strtotime($lawyer->updated_at) );
            })
            ->filterColumn('updated_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%y %H:%i') like ?", ["%$keyword%"]);
            })
            ->editColumn('name', function ($lawyer) {
                return $lawyer->name.' '.  Title::find($lawyer->title_id) ?? "" ;

            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereRaw("CONCAT(first_name,' ',last_name ) like ?", ["%$keyword%"]);
            })
            ->addColumn('rank', function($row){
                return  Rank::find($row->rank_id)->name;
            })
            ->addColumn('action', function($row){
                return "<a href='".route("lawyers.edit",["lawyer" => $row->id])."'
                        class='btn btn-success btn-sm mr-1 mb-1' data-toggle='tooltip' title='Edit Lawyer'>
                        <i class='fas fa-edit'></i> <span class='d-none d-md-inline'>Edit</span></a>

                        <button type='button' id='row_$row->id' onclick='deleteLawyer($row->id, this.id)'
                        class='btn btn-danger btn-sm' data-toggle='tooltip' title='Delete User'>
                        <i class='fas fa-trash'></i> <span class='d-none d-md-inline'>Delete</span></button>";
            })
            ->rawColumns(['image','action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $titles = Title::all();
        $ranks = Rank::all();
        return view("lawyers.create", compact("titles", "ranks"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLawyer $request
     * @return RedirectResponse
     */
    public function store(StoreLawyer $request)
    {
        return $request->validated();
        Lawyer::create($request->validated());

        Cache::increment('lawyers_count');

        return redirect(route('lawyers.index'))
            ->withSuccess("Lawyer Successfully Added!");
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  Lawyer  $lawyer
     * @return View
     */
    public function edit(Lawyer $lawyer)
    {
        $titles = Title::all();
        $ranks = Rank::all();
        return view("lawyers.edit", compact("lawyer", "titles", "ranks"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lawyer  $lawyer
     * @return \Illuminate\Http\Response
     */
    public function update(StoreLawyer $request, Lawyer $lawyer)
    {
        $lawyer->update($request->validated());

        return redirect(route('lawyers.index'))
            ->withSuccess("Lawyer Successfully Updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Lawyer $lawyer
     * @return bool|int
     * @throws \Exception
     */
    public function destroy(Lawyer $lawyer)
    {
        $lawyer->delete();
        return Cache::decrement('lawyers_count');
    }
}
