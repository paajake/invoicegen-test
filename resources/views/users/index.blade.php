@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
{{--    <h1 class="m-0 text-dark">Users</h1>--}}
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"style=" font-size:1.5rem">Users</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <div class="card-body">
                    <p class="mb-0">Manage the Users of the Application.
                    <a href="{{route('users.create')}}" class="btn btn-success float-right" data-toggle="tooltip" title="Add New User"><i class="fas fa-plus-circle"></i> <span class="d-none d-md-inline">Add</span></a></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="table">
                <table id="usersDataTable" class="table table-hover table-bordered data-table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Email</th>
                        <th>Updated</th>
                        <th width="100px">Action</th>
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

            $('#usersDataTable').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: "{{ route('users.index') }}",
                columns: [
                    {data: 'rownum', name: 'rownum', searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'image', name: 'image', orderable: false, searchable: false},
                    {data: 'email', name: 'email'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

        });

        //Delete User
        function deleteUser(user_id, button_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete User!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url:"{{url('users').'/'}}"+user_id,
                        type: 'post',
                        data: {
                            "id": user_id,
                            "_token": "{{ csrf_token() }}",
                            "_method": 'DELETE'
                        },
                        success: function ()
                        {
                            //Remove Row
                            $('#'+button_id).parent().parent().remove()
                            toastr.success("User Successfully Deleted!");
                        },
                        error: function (response) {
                            toastr.error("Something went wrong.");
                            console.log(response);
                        },
                    });
                }else{
                    // Swal.fire(
                    //     "Cancelled",
                    //     "User is safe :)",
                    //     "error");
                }
            });

        }

    </script>

@endsection

@section("footer").
@endsection

