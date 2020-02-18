@extends('adminlte::page')

@section('title', 'Ranks')

@section('content_header')
{{--    <h1 class="m-0 text-dark">Dashboard</h1>--}}
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"style=" font-size:1.5rem">Lawyers</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <div class="card-body">
                    <p class="mb-0">Manage Lawyer.
                        <a href="{{route('lawyers.create')}}" class="btn btn-success float-right" data-toggle="tooltip" title="Add New Lawyer"><i class="fas fa-plus-circle"></i> <span class="d-none d-md-inline">Add</span></a></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="table">
                <table id="lawyersDataTable" class="table table-hover table-bordered data-table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Rank</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Addon</th>
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

            $('#lawyersDataTable').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: "{{ route('lawyers.index') }}",
                columns: [
                    {data: 'rownum', name: 'rownum', searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'image', name: 'image', orderable: false, searchable: false},
                    {data: 'rank', name: 'rank', orderable: false, searchable: false},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'addon_rate', name: 'addon_rate'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

        });

        //Delete User
        function deleteLawyer(lawyer_id, button_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will Delete the Lawyer and All associated Data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete Lawyer!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url:"{{url('lawyers').'/'}}"+lawyer_id,
                        type: 'post',
                        data: {
                            "id": lawyer_id,
                            "_token": "{{ csrf_token() }}",
                            "_method": 'DELETE'
                        },
                        success: function ()
                        {
                            //Remove Row
                            $('#'+button_id).parent().parent().remove()
                            toastr.success("Lawyer Successfully Deleted!");
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
