<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\User;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Cache;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
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
    private function usersDataTable()
    {
        DB::statement(DB::raw('set @rownum=0'));

        $data = User::select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'name',
            'email',
            'image',
            'updated_at'
        ])->where("id", "!=", auth()->user()->id);

        return Datatables::of($data)
            ->editColumn('image', function ($user) {
                $url = Storage::url("public/images/uploads/".$user->image);
                return '<img src='.$url.' border="0" width="40" class="img-rounded m-auto"/>';
            })
            ->editColumn('updated_at', function ($user) {
                return date('d/m/y H:i', strtotime($user->updated_at) );
            })
            ->filterColumn('updated_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%y %H:%i') like ?", ["%$keyword%"]);
            })
            ->addColumn('action', function($row){
                return "<button type='button' id='row_$row->id' onclick='deleteUser($row->id, this.id)'
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
        return view("users.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUser $request
     * @return RedirectResponse
     */
    public function store(StoreUser $request)
    {
        $validated_values = $request->validated();
        unset($validated_values['image']);
        $image_name = "default.png";

        if ($request->hasFile('image')) {
            $image_name =  time().'.'.$request->file('image')->clientExtension();
            $request->file('image')->storeAs('public/images/uploads', $image_name, ["visibility" => "public"]);
        }

        $validated_values['image'] = $image_name;
        User::create($validated_values);

        Cache::increment('users_count');

        return redirect(route('users.index'))
                ->withSuccess("User Successfully Created!");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return View
     */
    public function edit(User $user)
    {
        return view("users.profile", compact("user"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreUser $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(StoreUser $request, User $user)
    {
        $image_name = $user->image;

        if ($request->hasFile('image')) {
            $image_name =  time().'.'.$request->file('image')->clientExtension();
            $request->file('image')->storeAs('public/images/uploads', $image_name, ["visibility" => "public"]);
        }

        $user->password = $request->validated()["password"];
        $user->name = $request->validated()["name"];
        $user->email = $request->validated()["email"];
        $user->image = $image_name;

        $user->save();


        return redirect(route('users.edit', ["user" => $user]))
            ->withSuccess("Account Successfully Updated!");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return bool|int
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        if ($user->image != "default.png"){
            Storage::delete("public/images/uploads/$user->image");
        }
        $user->delete();
        return Cache::decrement('users_count');
    }
}
