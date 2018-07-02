@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.new_employee') }}</h2>
        </div>
    </div>
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="tabs-container">
                    {{ Form::open(array('url' => '#', 'autocomplete' => 'off', 'class' => 'employee-form')) }}
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true">{{ trans('main.employee_data') }}</a></li>
                        <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">{{ trans('main.user_account_data') }}</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane active">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ trans('main.employee_code') }} *</label>
                                            {{ Form::text('code', null, array('class' => 'form-control code')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.employee_name') }} *</label>
                                            {{ Form::text('name', null, array('class' => 'form-control name')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.work_type') }} *</label>
                                            <div class="input-group">
                                                {{ Form::select('work_type', $work_types, null, array('class' => 'form-control work-type')) }}
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-info add-general-type" data-type="work-type"
                                                        data-insert-message="{{ trans('main.work_type_insert') }}">{{ trans('main.add') }}</button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.contract_type') }} *</label>
                                            <div class="input-group">
                                                {{ Form::select('contract_type', $contract_types, null, array('class' => 'form-control contract-type')) }}
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-info add-general-type" data-type="contract-type"
                                                        data-insert-message="{{ trans('main.contract_type_insert') }}">{{ trans('main.add') }}</button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.picture') }} *</label>
                                            {{ Form::file('picture', array('class' => 'form-control picture')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.sex') }} *</label>
                                            {{ Form::select('sex', array('M' => 'M', 'Ž' => 'Ž'), null, array('class' => 'form-control sex')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.contract_start_date') }} *</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                {{ Form::text('contract_start_date', null, array('class' => 'form-control contract-start-date')) }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.contract_expire_date') }}</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                {{ Form::text('contract_expire_date', null, array('class' => 'form-control contract-expire-date')) }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.medical_certificate_expire_date') }}</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                {{ Form::text('medical_certificate_expire_date', null, array('class' => 'form-control medical-certificate-expire-date')) }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.contract_end_date') }}</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                {{ Form::text('contract_end_date', null, array('class' => 'form-control contract-end-date')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ trans('main.oib') }} *</label>
                                            {{ Form::text('oib', null, array('class' => 'form-control oib')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.birth_date') }} *</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                {{ Form::text('birth_date', null, array('class' => 'form-control birth-date')) }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.citizenship') }} *</label>
                                            {{ Form::select('citizenship', $countries, null, array('class' => 'form-control citizenship')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.birth_city') }} *</label>
                                            {{ Form::text('birth_city', null, array('class' => 'form-control birth-city')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.country') }} *</label>
                                            {{ Form::select('country', $countries, null, array('class' => 'form-control country')) }}
                                        </div>
                                        <div class="form-group" id="city-id-div">
                                            <label>{{ trans('main.city') }} *</label>
                                            {{ Form::select('city_id', $cities, null, array('class' => 'form-control city-id')) }}
                                        </div>
                                        <div class="form-group" id="city-div" style="display: none">
                                            <label>{{ trans('main.city') }} *</label>
                                            {{ Form::text('city', null, array('class' => 'form-control city')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.address') }} *</label>
                                            {{ Form::text('address', null, array('class' => 'form-control address')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.phone') }} *</label>
                                            {{ Form::text('phone', null, array('class' => 'form-control phone')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.status') }} *</label>
                                            {{ Form::select('status', $statuses, null, array('class' => 'form-control status')) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab-2" class="tab-pane">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="m-b-md">
                                            <label class="">
                                                <div class="icheckbox_square-green" style="position: relative;">
                                                    <input type="checkbox" name="create_account_checkbox" class="i-checks create-account" style="position: absolute; opacity: 0;">
                                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0; padding: 0; background: rgb(255, 255, 255); border: 0; opacity: 0;"></ins>
                                                </div> {{ trans('main.create_account') }}
                                            </label>
                                        </div>
                                        {{ Form::hidden('create_account', 'F', array('class' => 'create-account-hidden')) }}
                                        <div class="form-group">
                                            <label>{{ trans('main.role_type') }}</label>
                                            {{ Form::select('user_role', $roles, null, array('class' => 'form-control user-role')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.email') }}</label>
                                            {{ Form::text('email', null, array('class' => 'form-control email')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.password') }}</label>
                                            {{ Form::password('password', array('class' => 'form-control password')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.repeat_password') }}</label>
                                            {{ Form::password('password_confirmation', array('class' => 'form-control password-confirmation')) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-t-xl">
                        <div class="col-sm-12">
                            <div class="text-center">
                                <a href="{{ route('GetEmployees') }}" class="btn btn-white m-t-n-xs"><strong>{{ trans('main.cancel') }}</strong></a>
                                <button type="button" class="btn btn-primary m-t-n-xs insert-employee"><strong>{{ trans('main.save') }}</strong></button>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    @include('modals.generalType')

    <script>

        var validation_error = '{{ trans('errors.validation_error') }}';
        var error = '{{ trans('errors.error') }}';

    </script>

    {{ HTML::script('js/functions/employees.js?v='.date('YmdHi')) }}
@endsection