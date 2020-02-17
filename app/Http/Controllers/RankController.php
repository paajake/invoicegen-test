<?php

namespace App\Http\Controllers;

use App\Rank;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;


class RankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->ranksDataTable();
        }

        return view("ranks.index");
    }

    /**
     * Returns Datatable for Users.
     */
    private function ranksDataTable()
    {
        DB::statement(DB::raw('set @rownum=0'));

        $data = Rank::select([
                                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                                'id',
                                'name',
                                'rate',
                                'updated_at'
                            ]);

        return Datatables::of($data)
            ->editColumn('updated_at', function ($rank)
            {
                return date('d/m/y H:i', strtotime($rank->updated_at) );
            })
            ->filterColumn('updated_at', function ($query, $keyword)
            {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%y %H:%i') like ?", ["%$keyword%"]);
            })
            ->addColumn('action', function($row)
            {
                return "<a href='".route("ranks.edit",["rank" => $row->id])."'
                        class='btn btn-success btn-sm' data-toggle='tooltip' title='Edit Rank'>
                        <i class='fas fa-edit'></i> <span class='d-none d-md-inline'>Edit</span></a>

                        <button type='button' id='row_$row->id' onclick='deleteRank($row->id, this.id)'
                        class='btn btn-danger btn-sm' data-toggle='tooltip' title='Delete Rank'>
                        <i class='fas fa-trash'></i> <span class='d-none d-md-inline'>Delete</span></button>";
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view("ranks.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store()
    {
        Rank::create($this->validateRank());

        Cache::increment('ranks_count');

        return redirect(route('ranks.index'))
            ->withSuccess("Rank Successfully Created!");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Rank  $rank
     * @return View
     */
    public function edit(Rank $rank)
    {
        return view("ranks.edit", compact("rank"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Rank  $rank
     * @return RedirectResponse
     */
    public function update(Rank $rank)
    {
        $rank->update($this->validateRank($rank->id));

        return redirect(route('ranks.index'))
            ->withSuccess("Rank Successfully Updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Rank $rank
     * @return bool|int
     * @throws \Exception
     */
    public function destroy(Rank $rank)
    {
        $rank->delete();

        return Cache::decrement('ranks_count');
    }

    private function validateRank($exclusion_id = 0){
        return request()->validate([
            "name" => "required|unique:ranks,name,$exclusion_id",
            "rate" => "required|numeric|min:1",
        ]);
    }
}
