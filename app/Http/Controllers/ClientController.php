<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
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
            return $this->clientsDataTable();
        }
        return view("clients.index");
    }

    /**
     * Returns Datatable for Users.
     */
    private function clientsDataTable()
    {
        DB::statement(DB::raw('set @rownum=0'));

        $data = Client::select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'name',
            'email',
            'phone',
            'updated_at'
        ]);

        return Datatables::of($data)
            ->editColumn('updated_at', function ($client)
            {
                return date('d/m/y H:i', strtotime($client->updated_at) );
            })
            ->filterColumn('updated_at', function ($query, $keyword)
            {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%y %H:%i') like ?", ["%$keyword%"]);
            })
            ->addColumn('action', function($row)
            {
                return "<a href='".route("clients.edit",["client" => $row->id])."'
                        class='btn btn-success btn-sm' data-toggle='tooltip' title='Edit Client'>
                        <i class='fas fa-edit'></i> <span class='d-none d-md-inline'>Edit</span></a>

                        <button type='button' id='row_$row->id' onclick='deleteClient($row->id, this.id)'
                        class='btn btn-danger btn-sm' data-toggle='tooltip' title='Delete Client'>
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
        return view("clients.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store()
    {
        Client::create($this->validateClient());

        Cache::increment('clients_count');

        return redirect(route('clients.index'))
            ->withSuccess("Client Successfully Created!");
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  Client  $client
     * @return View
     */
    public function edit(Client $client)
    {
        return view("clients.edit", compact("client"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Client  $client
     * @return RedirectResponse
     */
    public function update(Client $client)
    {
        $client->update($this->validateClient($client->id));

        return redirect(route('clients.index'))
            ->withSuccess("Client Successfully Updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Client $client
     * @return bool|int
     * @throws \Exception
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return Cache::decrement('clients_count');
    }

    private function validateClient($exclusion_id = 0){
        return request()->validate([
            "name" => "required|unique:clients,name,$exclusion_id",
            "email" => "required|email",
            "phone" => "sometimes|regex:/^[+]?\d{10,16}$/i",
        ],
        [
            'phone.regex' => 'Enter a valid phone number eg: +2331234567890',
        ]);
    }
}
