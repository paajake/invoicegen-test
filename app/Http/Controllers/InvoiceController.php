<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\StoreInvoice;
use App\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
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
            return $this->invoicesDataTable();
        }
        return view("invoices.index");
    }

    /**
     * Returns Datatable for Lawyers.
     * @throws \Exception
     */
    private function invoicesDataTable()
    {
        DB::statement(DB::raw('set @rownum=0'));

        $data = Invoice::join('clients', 'invoices.client_id', '=', 'clients.id')
            ->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'invoices.id',
                'clients.name as client',
                'start_date',
                'end_date',
                'invoice',
                'invoices.updated_at'
            ]);

        return Datatables::of($data)
            ->filterColumn('client', function ($query, $keyword) {
                $query->whereRaw("clients.name like ? ", ["%$keyword%"]);
            })
            ->editColumn('updated_at', function ($row) {
                return date('d/m/y H:i', strtotime($row->updated_at) );
            })
            ->filterColumn('updated_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(invoices.updated_at,'%d/%m/%y %H:%i') like ?", ["%$keyword%"]);
            })
            ->editColumn('start_date', function ($row) {
                return date('d/m/y', strtotime($row->start_date) );
            })
            ->filterColumn('start_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(invoices.start_date,'%d/%m/%y') like ?", ["%$keyword%"]);
            })
            ->editColumn('end_date', function ($row) {
                return date('d/m/y', strtotime($row->end_date) );
            })
            ->filterColumn('end_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(invoices.end_date,'%d/%m/%y') like ?", ["%$keyword%"]);
            })
            ->addColumn('action', function($row)
    {
                return "<a href='". Storage::url("public/docs/gen/".$row->invoice)."' target='_blank'
                        class='btn btn-primary btn-sm mr-1 mb-1' data-toggle='tooltip' title='Download Invoice'>
                        <i class='fas fa-download'></i> <span class='d-none d-md-inline'>Download</span></a>

                        <a href='".route("invoices.edit", ["invoice" => $row->id])."'
                        class='btn btn-success btn-sm mr-1 mb-1' data-toggle='tooltip' title='Edit Invoice'>
                        <i class='fas fa-edit'></i> <span class='d-none d-md-inline'>Edit</span></a>

                        <button type='button' id='row_$row->id' onclick='deleteInvoice($row->id, this.id)'
                        class='btn btn-danger btn-sm' data-toggle='tooltip' title='Delete Invoice'>
                        <i class='fas fa-trash'></i> <span class='d-none d-md-inline'>Delete</span></button>
                        ";
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
        $clients = Client::all();

        return view("invoices.create", compact("clients"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreInvoice $request
     * @return RedirectResponse
     */
    public function store(StoreInvoice $request)
    {
        Invoice::create(Invoice::preProcess($request));

        Cache::increment('invoices_count');

        return redirect(route('invoices.index'))
            ->withSuccess("Invoice Successfully Generated!");
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Invoice $invoice
     * @return View
     */
    public function edit(Invoice $invoice)
    {
        $clients = Client::all();

        return view("invoices.edit", compact("clients","invoice"));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param StoreInvoice $request
     * @param Invoice $invoice
     * @return RedirectResponse
     */
    public function update(StoreInvoice $request, Invoice $invoice)
    {
        $invoice_attributes = Invoice::preProcess($request);
        Storage::delete("public/docs/gen/$invoice->invoice");

        $invoice->update($invoice_attributes);

        return redirect(route('invoices.index'))
            ->withSuccess("Invoice Successfully Updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Invoice $invoice
     * @return bool|int
     * @throws \Exception
     */
    public function destroy(Invoice $invoice)
    {
        Storage::delete("public/docs/gen/$invoice->invoice");
        $invoice->delete();

        return Cache::decrement('invoices_count');
    }

}
