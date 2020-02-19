{{--                    <p class="login-box-msg">{{ __('adminlte::adminlte.register_message') }}</p>--}}
@if(isset($client))
    <form action="{{ route("clients.update", ["client" => $client->id]) }}" method="post">
    @method("PUT")
@else
    <form action="{{ route("clients.store") }}" method="post" >
@endif
    {{ csrf_field() }}


    <div class="input-group mb-3">
        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
               value="{{ old('name') ?? $client->name ?? null }}" placeholder="Name of Client" autofocus>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-university"></span>
            </div>
        </div>

        @if ($errors->has('name'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('name') }}</strong>
            </div>
        @endif
    </div>

    <div class="input-group mb-3">
        <input type="text" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
               value="{{old('email') ??  $client->email ?? null }}" placeholder="Email">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
        @if ($errors->has('email'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('email') }}</strong>
            </div>
        @endif
    </div>

    <div class="input-group mb-3">
        <input type="text" name="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
               value="{{ old('phone') ?? $client->phone ?? null }}" placeholder="Phone Number">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-phone"></span>
            </div>
        </div>
        @if ($errors->has('phone'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('phone') }}</strong>
            </div>
        @endif
    </div>

    <button type="submit" class="btn btn-primary btn-block btn-flat">
        @if(isset($client))
            Edit Client
        @else
            Add Client
        @endif
    </button>
</form>
