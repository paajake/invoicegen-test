{{--                    <p class="login-box-msg">{{ __('adminlte::adminlte.register_message') }}</p>--}}
@if(isset($user))
    <form action="{{ route("users.update", ["user" => $user->id]) }}" method="post" enctype="multipart/form-data">
    @method("PUT")
@else
    <form action="{{ route("users.store") }}" method="post" enctype="multipart/form-data">
@endif
    {{ csrf_field() }}

    <div class="input-group mb-3">
        <img src="{{ Storage::url("public/images/uploads/".($user->image ?? "default.png") )}}" id="preview" style="width: 150px" class="img-thumbnail rounded m-auto">
    </div>

    <div class="input-group mb-3">
        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $user->name ?? old('name') }}"
               placeholder="{{ __('adminlte::adminlte.full_name') }}" autofocus>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-user"></span>
            </div>
        </div>

        @if ($errors->has('name'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('name') }}</strong>
            </div>
        @endif
    </div>

    <div class="input-group mb-3">
        <input type="text"  disabled placeholder="Upload Image" id="image-text" class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}" value="{{ old('image') }}" autofocus>
        <input type="file" name="image"  class="file" accept="image/*"  style="visibility: hidden; position: absolute">

        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-image"></span>
            </div>
            <button type="button" class="browse btn btn-primary ">Browse ...</button>
        </div>

        @if ($errors->has('image'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('image') }}</strong>
            </div>
        @endif
    </div>

    <div class="input-group mb-3">
        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ $user->email ?? old('email') }}"
               placeholder="{{ __('adminlte::adminlte.email') }}">
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
        <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
               placeholder="{{ __('adminlte::adminlte.password') }}">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
        @if ($errors->has('password'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('password') }}</strong>
            </div>
        @endif
    </div>
    <div class="input-group mb-3">
        <input type="password" name="password_confirmation" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
               placeholder="{{ __('adminlte::adminlte.retype_password') }}">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
        @if ($errors->has('password_confirmation'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
            </div>
        @endif
    </div>
    <button type="submit" class="btn btn-primary btn-block btn-flat">
        @if(isset($user))
            Edit Account
        @else
            Add User
        @endif
    </button>
</form>
