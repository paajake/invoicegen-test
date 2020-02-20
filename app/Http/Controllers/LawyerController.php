<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLawyer;
use App\Lawyer;
use App\Rank;
use App\Title;
use Illuminate\Http\RedirectResponse;
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
     * @throws \Exception
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
     * @throws \Exception
     */
    private function lawyersDataTable()
    {
        DB::statement(DB::raw('set @rownum=0'));

        $data = Lawyer::leftJoin('titles', 'lawyers.title_id', '=', 'titles.id')
                        ->join('ranks', 'lawyers.rank_id', '=', 'ranks.id')
                        ->select([
                            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                            'lawyers.id',
                            DB::raw("CONCAT_WS(' ',lawyers.first_name,lawyers.last_name,titles.title ) as name"),
                            'title_id',
                            'image',
                            'ranks.name as rank',
                            'email',
                            'phone',
                            'addon_rate',
                            'lawyers.updated_at'
                        ]);

        return Datatables::of($data)
            ->editColumn('image', function ($lawyer) {
                $url = Storage::url("public/images/uploads/".$lawyer->image);
                return '<img src='.$url.' border="0" width="40" class="img-rounded m-auto"/>';
            })
            ->editColumn('updated_at', function ($lawyer) {
                return date('d/m/y H:i', strtotime($lawyer->updated_at) );
            })
            ->editColumn('email', function ($lawyer) {
                return "<a href='mailto:$lawyer->email'>$lawyer->email</a>";
            })
            ->editColumn('phone', function ($lawyer) {
                return "<a href='tel:$lawyer->phone'>$lawyer->phone</a>";
            })
            ->filterColumn('updated_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(lawyers.updated_at,'%d/%m/%y %H:%i') like ?", ["%$keyword%"]);
            })
            ->filterColumn('rank', function ($query, $keyword) {
                $query->whereRaw("ranks.name like ?", ["%$keyword%"]);
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereRaw("CONCAT(first_name,' ',last_name,' ', titles.title) like ?", ["%$keyword%"]);
            })
            ->addColumn('action', function($row){
                return "<a href='".route("lawyers.edit",["lawyer" => $row->id])."'
                        class='btn btn-success btn-sm mr-1 mb-1' data-toggle='tooltip' title='Edit Lawyer'>
                        <i class='fas fa-edit'></i> <span class='d-none d-md-inline'>Edit</span></a>

                        <button type='button' id='row_$row->id' onclick='deleteLawyer($row->id, this.id)'
                        class='btn btn-danger btn-sm' data-toggle='tooltip' title='Delete User'>
                        <i class='fas fa-trash'></i> <span class='d-none d-md-inline'>Delete</span></button>";
            })
            ->rawColumns(["image","action","email","phone"])
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
        Lawyer::create(Lawyer::preProcess($request));

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
     * @param StoreLawyer $request
     * @param Lawyer $lawyer
     * @return RedirectResponse
     */
    public function update(StoreLawyer $request, Lawyer $lawyer)
    {
        $lawyer->update(Lawyer::preProcess($request));

        return redirect(route('lawyers.index'))
            ->withSuccess("Lawyer Successfully Updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lawyer $lawyer
     * @return bool|int
     * @throws \Exception
     */
    public function destroy(Lawyer $lawyer)
    {
        if ($lawyer->image != "default.png"){
            Storage::delete("public/images/uploads/$lawyer->image");
        }

        $lawyer->delete();

        return Cache::decrement('lawyers_count');
    }
}
