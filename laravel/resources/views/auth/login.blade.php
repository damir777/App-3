@extends('layouts.auth')

@section('content')
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
                <h1 class="logo-name">&nbsp;</h1>
            </div>
            <h3>xx</h3>
            {{ Form::open(['route' => 'LoginUser', 'class' => 'm-t']) }}
                <div class="form-group">
                    {{ Form::text('email', null, array('class' => 'form-control', 'placeholder' => trans('main.email'),
                        'required')) }}
                </div>
                <div class="form-group">
                    {{ Form::password('password', array('class' => 'form-control', 'placeholder' => trans('main.password'),
                        'required')) }}
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">{{ trans('main.login') }}</button>
                <a href="{{ url('/password/reset') }}"><small>{{ trans('main.forgot_password') }}</small></a>
            {{ Form::close() }}
        </div>
    </div>
@endsection