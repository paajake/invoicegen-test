@extends('adminlte::page')

@section('title', 'Edit Rank')

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
                    <p class="mb-0">Edit TimeSheet.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6 m-auto">
            <div class="card">
                <div class="card-body register-card-body">
                    @include("timesheets._form")
                </div>
            </div>
        </div>
    </div>
@stop

@section('plugins.Select2', true)
@section('plugins.DateRangePicker', true)

@section('js')
    <script>
        //Select2
        $(document).ready(function() {
            $('#lawyer_id').select2({
                placeholder: "Select a Lawyer",
                allowClear: true
            });

            $('#client_id').select2({
                placeholder: "Select a Client",
                allowClear: true
            });


            $("#start_time").daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 10,
                minYear: 2010,
                maxYear: parseInt(moment().format('YYYY'),10),
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD H:mm:ss'
                }
            });

            $("#end_time").daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 10,
                minYear: 2010,
                maxYear: parseInt(moment().format('YYYY'),10),
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD H:mm:ss'
                }
            });

        });

    </script>
@endsection

@section("footer").
@endsection
