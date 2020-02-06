<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
use DB;
use Illuminate\Http\Request;
use App\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Cache;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->usersDataTable();
        }

        return view('users.index');
    }

    /**
     * Returns Datatable for Users.
     */
    private function usersDataTable(){
        DB::statement(DB::raw('set @rownum=0'));

        $data = User::select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'name',
            'email',
            'created_at'
        ])->where("id","!=",auth()->user()->id);

        return Datatables::of($data)
            ->editColumn('created_at', function ($user) {
                return date('d/m/y H:i', strtotime($user->created_at) );
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%y %H:%i') like ?", ["%$keyword%"]);
            })
            ->addColumn('action', function($row){
                return "<button type='button' id='row_$row->id' onclick='deleteUser($row->id, this.id)' class='btn btn-danger btn-sm' data-toggle='ooltip' title='Delete User'><i class='fas fa-trash'></i> <span class='d-none d-md-inline'>Delete</span></button>";
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view("users.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function store(StoreUser $request)
    {
        $validated_values = $request->validated();
        unset($validated_values['image']);
        $image_name = null;

        if ($request->hasFile('image')) {
            $image_name =  time().'.'.$request->file('image')->clientExtension();
            $request->file('image')->storeAs('images/uploads', $image_name);
        }

        $validated_values['image'] = $image_name;

        User::create($validated_values);

        Cache::increment('users_count');

        return redirect(route('users.index'))
                ->withSuccess("User Successfully Created!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return bool|int
     */
    public function destroy(Request $request)
    {
        $num_of_del_users = User::destroy( $request->get("id"));

        return Cache::decrement('users_count',$num_of_del_users);
    }
}
