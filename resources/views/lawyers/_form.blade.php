{{--                    <p class="login-box-msg">{{ __('adminlte::adminlte.register_message') }}</p>--}}
@if(isset($lawyer))
    <form action="{{ route("lawyers.update", ["lawyer" => $lawyer->id]) }}" method="post" enctype="multipart/form-data">
    @method("PUT")
@else
    <form action="{{ route("lawyers.store") }}" method="post" enctype="multipart/form-data">
@endif
    {{ csrf_field() }}

    <div class="input-group mb-3">
        <img src="{{ Storage::url("public/images/uploads/".($lawyer->image ?? "default.png") )}}" id="preview" style="width: 150px" class="img-thumbnail rounded m-auto">
    </div>

    <div class="input-group mb-3">
        <select class="form-control select2" style="width: 100%;" autofocus id="title_id" name="title_id">
            @foreach($titles as $title)
                <option value = "{{$title->id}}" @if ($title->id == ( old('title_id') ?? $lawyer->title_id ?? null ))
                    selected="selected" @endif >{{ $title->title }}
                </option>
            @endforeach
        </select>
{{--        <div class="input-group-append">--}}
{{--            <div class="input-group-text">--}}
{{--                <span class="fas fa-user"></span>--}}
{{--            </div>--}}
{{--        </div>--}}

        @if ($errors->has('first_name'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('first_name') }}</strong>
            </div>
        @endif
    </div>

    <div class="input-group mb-3">
        <input type="text" name="first_name" class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
               value="{{ old('first_name') ?? $lawyer->first_name ?? null}}" placeholder="First Name" autofocus>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-user"></span>
            </div>
        </div>

        @if ($errors->has('first_name'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('first_name') }}</strong>
            </div>
        @endif
    </div>

    <div class="input-group mb-3">
        <input type="text" name="last_name" class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
               value="{{ old('last_name') ?? $lawyer->last_name ?? null }}" placeholder="Last Name" autofocus>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-user"></span>
            </div>
        </div>

        @if ($errors->has('last_name'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('last_name') }}</strong>
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
        <select id="rank_id" class="form-control select2" style="width: 100%;" autofocus name="rank_id">
            @foreach($ranks as $rank)
                <option value = "{{$rank->id}}" @if ($rank->id == (  old('rank_id') ?? $lawyer->rank_id ?? null ))
                selected="selected" @endif >{{ $rank->name }}
                </option>
            @endforeach
        </select>
{{--        <div class="input-group-append">--}}
{{--            <div class="input-group-text">--}}
{{--                <span class="fas fa-university"></span>--}}
{{--            </div>--}}
{{--        </div>--}}

        @if ($errors->has('rank_id'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('rank_id') }}</strong>
            </div>
        @endif
    </div>

    <div class="input-group mb-3">
        <input type="number" name="addon_rate" step="0.01" class="form-control {{ $errors->has('addon_rate') ? 'is-invalid' : '' }}"
               value="{{ old('addon_rate') ?? $lawyer->addon_rate ?? null }}" placeholder="Addon Percentage">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-money-bill-alt"></span>
            </div>
        </div>
        @if ($errors->has('addon_rate'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('addon_rate') }}</strong>
            </div>
        @endif
    </div>

    <div class="input-group mb-3">
        <input type="text" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
               value="{{ old('email') ?? $lawyer->email ?? null }}" placeholder="Email">
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
               value="{{ old('phone') ?? $lawyer->phone ?? null }}" placeholder="Phone Number">
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
        @if(isset($lawyer))
            Edit Lawyer
        @else
            Add Lawyer
        @endif
    </button>
</form>
