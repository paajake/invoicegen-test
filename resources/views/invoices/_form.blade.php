{{--                    <p class="login-box-msg">{{ __('adminlte::adminlte.register_message') }}</p>--}}
@if(isset($invoice))
    <form action="{{ route("invoices.update", ["invoice" => $invoice->id]) }}" method="post">
    @method("PUT")
@else
    <form action="{{ route("invoices.store") }}" method="post" >
@endif
    {{ csrf_field() }}

    <div class="input-group mb-3">
        <select class="form-control select2" style="width: 100%;" autofocus id="client_id" name="client_id">
            <option></option>
            @foreach($clients as $client)
                <option value = "{{$client->id}}" @if ($client->id == ( old('client_id') ?? $invoice->client_id ?? null ))
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
        <input type="text" id="date_range" name="date_range" class="form-control {{ $errors->has('date_range') ? 'is-invalid' : '' }}"
               value="{{ old('date_range') ?? ( isset($invoice) ? $invoice->start_date->format("Y-m-d").' - '.$invoice->end_date->format("Y-m-d") : null )}}" placeholder="Date Range">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-calendar"></span>
            </div>
        </div>
        @if ($errors->has('date_range'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('date_range') }}</strong>
            </div>
        @endif
    </div>

    <button type="submit" class="btn btn-primary btn-block btn-flat">
        @if(isset($invoice))
            Update Invoice
        @else
            Generate Invoice
        @endif
    </button>
</form>
