{{--                    <p class="login-box-msg">{{ __('adminlte::adminlte.register_message') }}</p>--}}
@if(isset($rank))
    <form action="{{ route("ranks.update", ["rank" => $rank->id]) }}" method="post">
    @method("PUT")
@else
    <form action="{{ route("ranks.store") }}" method="post" >
@endif
    {{ csrf_field() }}


    <div class="input-group mb-3">
        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
               value="{{  old('name') ?? $rank->name ?? null}}" placeholder="Name of Rank" autofocus>
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
        <input type="number" name="rate" step="0.01" class="form-control {{ $errors->has('rate') ? 'is-invalid' : '' }}"
               value="{{  old('rate')  ?? $rank->rate ?? null }}" placeholder="Rate Per Hour for this Rank">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-money-bill-alt"></span>
            </div>
        </div>
        @if ($errors->has('rate'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('rate') }}</strong>
            </div>
        @endif
    </div>

    <button type="submit" class="btn btn-primary btn-block btn-flat">
        @if(isset($rank))
            Edit Rank
        @else
            Add Rank
        @endif
    </button>
</form>
