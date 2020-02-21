{{--                    <p class="login-box-msg">{{ __('adminlte::adminlte.register_message') }}</p>--}}
@if(isset($timesheet))
    <form action="{{ route("timesheets.update", ["timesheet" => $timesheet->id]) }}" method="post">
    @method("PUT")
@else
    <form action="{{ route("timesheets.store") }}" method="post" >
@endif
    {{ csrf_field() }}

    <div class="input-group mb-3">
        <select class="form-control select2" style="width: 100%;" autofocus id="lawyer_id" name="lawyer_id">
            <option></option>
            @foreach($lawyers as $lawyer)
                <option value = "{{$lawyer->id}}" @if ($lawyer->id == ( old('lawyer_id') ?? $timesheet->lawyer_id ?? null ))
                    selected="selected" @endif >{{ $lawyer->first_name. ' '.$lawyer->last_name. ' '.$lawyer->title()->pluck("title")[0] }}
                </option>
            @endforeach
        </select>
{{--        <div class="input-group-append">--}}
{{--            <div class="input-group-text">--}}
{{--                <span class="fas fa-user"></span>--}}
{{--            </div>--}}
{{--        </div>--}}

        @if ($errors->has('lawyer_id'))
            <div style="display: block;" class="invalid-feedback">
                <strong>{{ $errors->first('lawyer_id') }}</strong>
            </div>
        @endif
    </div>

    <div class="input-group mb-3">
        <select class="form-control select2" style="width: 100%;" autofocus id="client_id" name="client_id">
            <option></option>
            @foreach($clients as $client)
                <option value = "{{$client->id}}" @if ($client->id == ( old('client_id') ?? $timesheet->client_id ?? null ))
                selected="selected" @endif >{{ $client->name }}
                </option>
            @endforeach
        </select>
        {{--        <div class="input-group-append">--}}
        {{--            <div class="input-group-text">--}}
        {{--                <span class="fas fa-user"></span>--}}
        {{--            </div>--}}
        {{--        </div>--}}

        @if ($errors->has('client_id'))
            <div style="display: block;" class="invalid-feedback">
                <strong>{{ $errors->first('client_id') }}</strong>
            </div>
        @endif
    </div>

    <div class="input-group mb-3">
        <input type="text" id="start_time" name="start_time" class="form-control {{ $errors->has('start_time') ? 'is-invalid' : '' }}"
               value="{{ old('start_time') ?? ( isset($timesheet) ? $timesheet->start_time->format("Y-m-d H:i:s") : null )}}" placeholder="Start Time">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="far fa-clock"></span>
            </div>
        </div>
        @if ($errors->has('start_time'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('start_time') }}</strong>
            </div>
        @endif
    </div>

    <div class="input-group mb-3">
        <input type="text" id="end_time" name="end_time" class="form-control {{ $errors->has('end_time') ? 'is-invalid' : '' }}"
               value="{{ old('end_time') ?? ( isset($timesheet) ? $timesheet->end_time->format("Y-m-d H:i") : null )}}" placeholder="End Time">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="far fa-clock"></span>
            </div>
        </div>
        @if ($errors->has('end_time'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('end_time') }}</strong>
            </div>
        @endif
    </div>

    <button type="submit" class="btn btn-primary btn-block btn-flat">
        @if(isset($timesheet))
            Edit TimeSheet
        @else
            Add TimeSheet
        @endif
    </button>
</form>
