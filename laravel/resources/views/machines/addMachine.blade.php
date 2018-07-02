@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.new_machine') }}</h2>
        </div>
    </div>
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                {{ Form::open(array('url' => '#', 'autocomplete' => 'off', 'class' => 'machine-form')) }}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ trans('main.code') }} *</label>
                            {{ Form::text('code', null, array('class' => 'form-control code')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.manufacturer') }} *</label>
                            <div class="input-group">
                                {{ Form::select('manufacturer', $manufacturers, null, array('class' => 'form-control manufacturer')) }}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info add-manufacturer">{{ trans('main.add') }}</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.name') }} *</label>
                            {{ Form::text('name', null, array('class' => 'form-control name')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.model') }} *</label>
                            {{ Form::text('model', null, array('class' => 'form-control model')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.picture') }} *</label>
                            {{ Form::file('picture', array('class' => 'form-control picture')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.manufacture_year') }} *</label>
                            {{ Form::text('manufacture_year', null, array('class' => 'form-control manufacture-year')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.serial_number') }} *</label>
                            {{ Form::text('serial_number', null, array('class' => 'form-control serial-number')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.mass') }} *</label>
                            {{ Form::text('mass', null, array('class' => 'form-control mass')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.type') }} *</label>
                            <div class="input-group">
                                {{ Form::select('type', $types, null, array('class' => 'form-control machine-type')) }}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info add-general-type" data-type="machine-type"
                                        data-insert-message="{{ trans('main.machine_type_insert') }}">{{ trans('main.add') }}</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.notes') }}</label>
                            {{ Form::textarea('notes', null, array('class' => 'form-control notes')) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ trans('main.pin') }} *</label>
                            {{ Form::text('pin', null, array('class' => 'form-control pin')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.purchase_date') }} *</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {{ Form::text('purchase_date', null, array('class' => 'form-control purchase-date')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.sale_date') }}</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {{ Form::text('sale_date', null, array('class' => 'form-control sale-date')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.start_working_hours') }} *</label>
                            {{ Form::text('start_working_hours', null, array('class' => 'form-control start-working-hours')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.end_working_hours') }}</label>
                            {{ Form::text('end_working_hours', null, array('class' => 'form-control end-working-hours')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.register_number') }}</label>
                            {{ Form::text('register_number', null, array('class' => 'form-control register-number')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.register_date') }}</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {{ Form::text('register_date', null, array('class' => 'form-control register-date')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.certificate_end_date') }} *</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {{ Form::text('certificate_end_date', null, array('class' => 'form-control certificate-end-date')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.status') }} *</label>
                            {{ Form::select('status', $statuses, null, array('class' => 'form-control status')) }}
                        </div>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        <div class="text-center">
                            <a href="{{ route('GetMachines') }}" class="btn btn-white m-t-n-xs"><strong>{{ trans('main.cancel') }}</strong></a>
                            <button type="button" class="btn btn-primary m-t-n-xs insert-machine"><strong>{{ trans('main.save') }}</strong></button>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    @include('modals.manufacturer')
    @include('modals.generalType')

    <script>

        var manufacturer_insert = '{{ trans('main.manufacturer_insert') }}';
        var validation_error = '{{ trans('errors.validation_error') }}';
        var error = '{{ trans('errors.error') }}';

    </script>

    {{ HTML::script('js/functions/machines.js?v='.date('YmdHi')) }}
@endsection