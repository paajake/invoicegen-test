@extends('adminlte::page')

@section('title', 'TimeSheets')

@section('content_header')
{{--    <h1 class="m-0 text-dark">Dashboard</h1>--}}
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"style=" font-size:1.5rem">TimeSheets</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <div class="card-body">
                    <p class="mb-0">Manage Lawyers' TimeSheets.
                        <a href="{{route('timesheets.create')}}" class="btn btn-success float-right" data-toggle="tooltip" title="Add New TimeSheet"><i class="fas fa-plus-circle"></i> <span class="d-none d-md-inline">Add</span></a>
                        <a href="{{route('timesheets.upload')}}" class="btn btn-primary float-right mr-1" data-toggle="tooltip" title="Upload Timesheet"><i class="fas fa-file-upload"></i> <span class="d-none d-md-inline">Upload</span></a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="table">
                <table id="timeSheetsDataTable" class="table table-hover table-bordered data-table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Lawyer</th>
                        <th>Client</th>
                        <th>Day</th>
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

{{--    Modal For Upload TimeSheet--}}
    <div class="row">
        <div class="col-12">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Open modal for @mdo</button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@fat">Open modal for @fat</button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap">Open modal for @getbootstrap</button>

            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Recipient:</label>
                                    <input type="text" class="form-control" id="recipient-name">
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="col-form-label">Message:</label>
                                    <textarea class="form-control" id="message-text"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Send message</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('plugins.Datatables', true)

@section("js")
    <script>
        // Users DataTable
        $(function () {

            $('#timeSheetsDataTable').DataTable({
                processing: false,
                serverSide: true,
                stateSave: true,
                ajax: "{{ route('timesheets.index') }}",
                columns: [
                    {data: 'rownum', name: 'rownum', searchable: false},
                    {data: 'lawyer', name: 'lawyer'},
                    {data: 'client', name: 'client'},
                    {data: 'day', name: 'day'},
                    {data: 'start_time', name: 'start_time'},
                    {data: 'end_time', name: 'end_time'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

        });

        //Delete User
        function deleteTimeSheet(timesheet_id, button_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will Delete the TimeSheet and All associated Data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete TimeSheet!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url:"{{url('timesheets').'/'}}"+timesheet_id,
                        type: 'post',
                        data: {
                            "id": timesheet_id,
                            "_token": "{{ csrf_token() }}",
                            "_method": 'DELETE'
                        },
                        success: function ()
                        {
                            //Remove Row
                            $('#'+button_id).parent().parent().remove()
                            toastr.success("TimeSheet Successfully Deleted!");
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
