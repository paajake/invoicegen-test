@extends('adminlte::page')

@section('title', 'Upload TimeSheet')

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
                    <p class="mb-0">Upload TimeSheet</p>
                    <p class="mb-0"><b>Note :</b> Lawyer ID and Client MUST already exist in the DataBase,
                        <a href="{{Storage::url("public/docs/uploads/test_timesheet.csv")}}" target="_blank">
                        click here for a sample TimeSheet</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6 m-auto">
            <div class="card">
                <div class="card-body register-card-body">
                    <form action="{{ route("lawyers.store") }}" method="post" enctype="multipart/form-data">
                        <div class="input-group mb-3">
                            <input type="text"  disabled placeholder="Upload TimeSheet" id="image-text" class="form-control {{ $errors->has('timesheet') ? 'is-invalid' : '' }}" value="{{ old('timesheet') }}" autofocus>
                            <input type="file" name="timesheet"  class="file" accept=".csv"  style="visibility: hidden; position: absolute">

                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-image"></span>
                                </div>
                                <button type="button" class="browse btn btn-primary ">Browse ...</button>
                            </div>

                            @if ($errors->has('timesheet'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('timesheet') }}</strong>
                                </div>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-flat"> Upload TimeSheet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section("JS")
    <script>
        $(document).on("click", ".browse", function() {
            let file = $(this).parents().find(".file");
            file.trigger("click");
        });
        $('input[type="file"]').change(function(e) {
            let fileName = e.target.files[0].name;
            $("#file-text").val(fileName);

        });
    </script>
@endsection

@section("footer").
@endsection
