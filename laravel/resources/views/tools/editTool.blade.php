@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.edit_tool') }}</h2>
        </div>
    </div>
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                {{ Form::open(array('url' => '#', 'autocomplete' => 'off', 'class' => 'tool-form')) }}
                {{ Form::hidden('id', $tool->id) }}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ trans('main.code') }} *</label>
                            {{ Form::text('code', $tool->code, array('class' => 'form-control code')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.manufacturer') }} *</label>
                            <div class="input-group">
                                {{ Form::select('manufacturer', $manufacturers, $tool->manufacturer_id, array('class' => 'form-control manufacturer')) }}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info add-manufacturer">{{ trans('main.add') }}</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.name') }} *</label>
                            {{ Form::text('name', $tool->name, array('class' => 'form-control name')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.model') }} *</label>
                            {{ Form::text('model', $tool->model, array('class' => 'form-control model')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.picture') }} *</label>
                            {{ Form::file('picture', array('class' => 'form-control picture')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.manufacture_year') }} *</label>
                            {{ Form::text('manufacture_year', $tool->manufacture_year, array('class' => 'form-control manufacture-year')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.serial_number') }} *</label>
                            {{ Form::text('serial_number', $tool->serial_number, array('class' => 'form-control serial-number')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.notes') }}</label>
                            {{ Form::textarea('notes', $tool->notes, array('class' => 'form-control notes')) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ trans('main.mass') }} *</label>
                            {{ Form::text('mass', $tool->mass, array('class' => 'form-control mass')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.type') }} *</label>
                            <div class="input-group">
                                {{ Form::select('type', $types, $tool->tool_type_id, array('class' => 'form-control tool-type')) }}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info add-general-type" data-type="tool-type"
                                        data-insert-message="{{ trans('main.tool_type_insert') }}">{{ trans('main.add') }}</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.internal_code') }} *</label>
                            {{ Form::text('internal_code', $tool->internal_code, array('class' => 'form-control internal-code')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.purchase_date') }} *</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {{ Form::text('purchase_date', $tool->purchase_date, array('class' => 'form-control purchase-date')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.sale_date') }}</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {{ Form::text('sale_date', $tool->sale_date, array('class' => 'form-control sale-date')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.status') }} *</label>
                            {{ Form::select('status', $statuses, $tool->status_id, array('class' => 'form-control status')) }}
                        </div>
                        <div class="asset-image">
                            {{ HTML::image($tool->picture, '', array('class' => 'img img-responsive')) }}
                        </div>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        <div class="text-center">
                            <a href="{{ route('GetTools') }}" class="btn btn-white m-t-n-xs"><strong>{{ trans('main.cancel') }}</strong></a>
                            <button type="button" class="btn btn-primary m-t-n-xs update-tool"><strong>{{ trans('main.save') }}</strong></button>
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