@extends('adminlte::page')

@section('title', 'Add Lawyer')

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
                    <p class="mb-0">Add New Lawyer</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6 m-auto">
            <div class="card">
                <div class="card-body register-card-body">
                    @include("lawyers._form")
                </div>
            </div>
        </div>
    </div>
@stop

@section('plugins.Select2', true)

@section('js')
    <script>
        //Select2
        $(document).ready(function() {
            $('#title_id').select2({
                placeholder: "Select a Title",
                allowClear: true
            });

            $('#rank_id').select2({
                placeholder: "Select a Rank"
            });
        });


        $(document).on("click", ".browse", function() {
            let file = $(this).parents().find(".file");
            file.trigger("click");
        });
        $('input[type="file"]').change(function(e) {
            let fileName = e.target.files[0].name;
            $("#image-text").val(fileName);

            let reader = new FileReader();
            reader.onload = function(e) {
                // get loaded data and render thumbnail.
                document.getElementById("preview").src = e.target.result;
            };
            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        });
    </script>
@endsection

@section("footer").
@endsection
