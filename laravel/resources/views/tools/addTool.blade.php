@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.new_tool') }}</h2>
        </div>
    </div>
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                {{ Form::open(array('url' => '#', 'autocomplete' => 'off', 'class' => 'tool-form')) }}
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
                            <label>{{ trans('main.notes') }}</label>
                            {{ Form::textarea('notes', null, array('class' => 'form-control notes')) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ trans('main.mass') }} *</label>
                            {{ Form::text('mass', null, array('class' => 'form-control mass')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.type') }} *</label>
                            <div class="input-group">
                                {{ Form::select('type', $types, null, array('class' => 'form-control tool-type')) }}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info add-general-type" data-type="tool-type"
                                        data-insert-message="{{ trans('main.tool_type_insert') }}">{{ trans('main.add') }}</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.internal_code') }} *</label>
                            {{ Form::text('internal_code', null, array('class' => 'form-control internal-code')) }}
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
                            <label>{{ trans('main.status') }} *</label>
                            {{ Form::select('status', $statuses, null, array('class' => 'form-control status')) }}
                        </div>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        <div class="text-center">
                            <a href="{{ route('GetTools') }}" class="btn btn-white m-t-n-xs"><strong>{{ trans('main.cancel') }}</strong></a>
                            <button type="button" class="btn btn-primary m-t-n-xs insert-tool"><strong>{{ trans('main.save') }}</strong></button>
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

    {{ HTML::script('js/functions/tools.js?v='.date('YmdHi')) }}
@endsection