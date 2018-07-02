@extends('layouts.auth')

@section('content')
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <h1 class="logo-name">&nbsp;</h1>
            <h3>{{ trans('main.reset_password') }}</h3>
            {{ Form::open(array('url' => '/password/email', 'class' => 'm-t')) }}
            <div class="form-group @if ($errors->has('email')) has-error @endif">
                {{ Form::text('email', old('email'), array('class' => 'form-control', 'placeholder' => trans('main.email'),
                'required')) }}
                @if ($errors->has('email'))
                    <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                @endif
            </div>
            <button type="submit" class="btn btn-primary block full-width">
                <i class="fa fa-btn fa-envelope"></i> {{ trans('main.send_reset_password_link') }}
            </button>
            {{ Form::close() }}
        </div>
    </div>
@endsection