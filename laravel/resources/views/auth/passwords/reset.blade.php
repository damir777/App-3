@extends('layouts.auth')

@section('content')
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <h1 class="logo-name">&nbsp;</h1>
            <h3>{{ trans('main.reset_password') }}</h3>
            {{ Form::open(array('url' => '/password/reset', 'class' => 'm-t')) }}
            {{ Form::hidden('token', $token) }}
            <div class="form-group @if ($errors->has('email')) has-error @endif">
                {{ Form::text('email', $email or old('email'), array('class' => 'form-control', 'placeholder' => trans('main.email'),
                    'required')) }}
                @if ($errors->has('email'))
                    <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                @endif
            </div>
            <div class="form-group @if ($errors->has('password')) has-error @endif">
                {{ Form::password('password', array('class' => 'form-control', 'placeholder' => trans('main.password'),
                    'required')) }}
                @if ($errors->has('password'))
                    <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                @endif
            </div>
            <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
                {{ Form::password('password_confirmation', array('class' => 'form-control',
                    'placeholder' => trans('main.repeat_password'), 'required')) }}
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                @endif
            </div>
            <button type="submit" class="btn btn-primary block full-width">
                <i class="fa fa-btn fa-refresh"></i> {{ trans('main.reset_password_button') }}
            </button>
            {{ Form::close() }}
        </div>
    </div>
@endsection