@extends('adminlte::page')

@section('title', 'Invoices')

@section('content_header')
{{--    <h1 class="m-0 text-dark">Dashboard</h1>--}}
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"style=" font-size:1.5rem">Invoices</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <div class="card-body">
                    <p class="mb-0">Manage Invoices.
                        <a href="{{route('invoices.create')}}" class="btn btn-success float-right" data-toggle="tooltip" title="Generate New Invoice"><i class="fas fa-plus-circle"></i> <span class="d-none d-md-inline">Add</span></a></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="table">
                <table id="invoicesDataTable" class="table table-hover table-bordered data-table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Client</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Updated</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('plugins.Datatables', true)

@section("js")
    <script>
        // Users DataTable
        $(function () {

            $('#invoicesDataTable').DataTable({
                processing: false,
                serverSide: true,
                stateSave: true,
                ajax: "{{ route('invoices.index') }}",
                columns: [
                    {data: 'rownum', name: 'rownum', searchable: false},
                    {data: 'client', name: 'client'},
                    {data: 'start_date', name: 'start_date'},
                    {data: 'end_date', name: 'end_date'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

        });

        //Delete User
        function deleteInvoice(invoice_id, button_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will Delete the Generated Invoice Completely!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete Invoice!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url:"{{url('invoices').'/'}}"+invoice_id,
                        type: 'post',
                        data: {
                            "id": invoice_id,
                            "_token": "{{ csrf_token() }}",
                            "_method": 'DELETE'
                        },
                        success: function ()
                        {
                            //Remove Row
                            $('#'+button_id).parent().parent().remove()
                            toastr.success("Invoice Successfully Deleted!");
                        },
                        error: function (response) {
                            toastr.error("Something went wrong.");
                            console.log(response);
                        },
                    });
                }else{

                }
            });

        }

    </script>

@endsection
@section("footer").
@endsection
