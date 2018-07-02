@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.edit_vehicle') }}</h2>
        </div>
    </div>
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                {{ Form::open(array('url' => '#', 'autocomplete' => 'off', 'class' => 'vehicle-form')) }}
                {{ Form::hidden('id', $vehicle->id) }}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ trans('main.code') }} *</label>
                            {{ Form::text('code', $vehicle->code, array('class' => 'form-control code')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.manufacturer') }} *</label>
                            <div class="input-group">
                                {{ Form::select('manufacturer', $manufacturers, $vehicle->manufacturer_id, array('class' => 'form-control manufacturer')) }}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info add-manufacturer">{{ trans('main.add') }}</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.name') }} *</label>
                            {{ Form::text('name', $vehicle->name, array('class' => 'form-control name')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.model') }} *</label>
                            {{ Form::text('model', $vehicle->model, array('class' => 'form-control model')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.picture') }} *</label>
                            {{ Form::file('picture', array('class' => 'form-control picture')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.manufacture_year') }} *</label>
                            {{ Form::text('manufacture_year', $vehicle->manufacture_year, array('class' => 'form-control manufacture-year')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.mass') }} *</label>
                            {{ Form::text('mass', $vehicle->mass, array('class' => 'form-control mass')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.type') }} *</label>
                            <div class="input-group">
                                {{ Form::select('type', $types, $vehicle->vehicle_type_id, array('class' => 'form-control vehicle-type')) }}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info add-general-type" data-type="vehicle-type"
                                        data-insert-message="{{ trans('main.vehicle_type_insert') }}">{{ trans('main.add') }}</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.seats_number') }} *</label>
                            {{ Form::text('seats_number', $vehicle->seats_number, array('class' => 'form-control seats-number')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.notes') }}</label>
                            {{ Form::textarea('notes', $vehicle->notes, array('class' => 'form-control notes')) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ trans('main.chassis_number') }} *</label>
                            {{ Form::text('chassis_number', $vehicle->chassis_number, array('class' => 'form-control chassis-number')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.fuel_type') }} *</label>
                            {{ Form::select('fuel_type', $fuel_types, $vehicle->fuel_type_id, array('class' => 'form-control fuel-type')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.purchase_date') }} *</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {{ Form::text('purchase_date', $vehicle->purchase_date, array('class' => 'form-control purchase-date')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.sale_date') }}</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {{ Form::text('sale_date', $vehicle->sale_date, array('class' => 'form-control sale-date')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.start_mileage') }} *</label>
                            {{ Form::text('start_mileage', $vehicle->start_mileage, array('class' => 'form-control start-mileage')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.end_working_hours') }}</label>
                            {{ Form::text('end_working_hours', $vehicle->end_working_hours, array('class' => 'form-control end-working-hours')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.register_number') }} *</label>
                            {{ Form::text('register_number', $vehicle->register_number, array('class' => 'form-control register-number')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.register_date') }} *</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {{ Form::text('register_date', $vehicle->register_date, array('class' => 'form-control register-date')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.status') }} *</label>
                            {{ Form::select('status', $statuses, $vehicle->status_id, array('class' => 'form-control status')) }}
                        </div>
                        <div class="asset-image">
                            {{ HTML::image($vehicle->picture, '', array('class' => 'img img-responsive')) }}
                        </div>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        <div class="text-center">
                            <a href="{{ route('GetVehicles') }}" class="btn btn-white m-t-n-xs"><strong>{{ trans('main.cancel') }}</strong></a>
                            <button type="button" class="btn btn-primary m-t-n-xs update-vehicle"><strong>{{ trans('main.save') }}</strong></button>
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
        var validation_error = '{{ trans('main.validation_error') }}';
        var error = '{{ trans('errors.error') }}';

    </script>

    {{ HTML::script('js/functions/vehicles.js?v='.date('YmdHi')) }}
@endsection