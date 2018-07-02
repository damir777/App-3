@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.edit_site') }}</h2>
        </div>
    </div>
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                {{ Form::open(array('url' => '#', 'autocomplete' => 'off', 'class' => 'site-form')) }}
                {{ Form::hidden('id', $site->id, array('id' => 'site_id')) }}
                {{ Form::hidden('latitude', $site->latitude, array('id' => 'latitude')) }}
                {{ Form::hidden('longitude', $site->longitude, array('id' => 'longitude')) }}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ trans('main.code') }} *</label>
                            {{ Form::text('code', $site->code, array('class' => 'form-control code')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.name') }} *</label>
                            <div class="input-group">
                                {{ Form::text('name', $site->name, array('class' => 'form-control name')) }}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#locationModal">
                                        <i class="fa fa-map-marker fa-lg" aria-hidden="true"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.country') }} *</label>
                            {{ Form::select('country', $countries, $site->country_id, array('class' => 'form-control country')) }}
                        </div>
                        <div class="form-group" id="city-id-div">
                            <label>{{ trans('main.city') }} *</label>
                            {{ Form::select('city_id', $cities, $site->city_id, array('class' => 'form-control city-id')) }}
                        </div>
                        <div class="form-group" id="city-div" style="display: none">
                            <label>{{ trans('main.city') }} *</label>
                            {{ Form::text('city', $site->city, array('class' => 'form-control city')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.notes') }}</label>
                            {{ Form::textarea('notes', $site->notes, array('class' => 'form-control notes')) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ trans('main.address') }} *</label>
                            {{ Form::text('address', $site->address, array('class' => 'form-control address')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.investor') }} *</label>
                            <div class="input-group">
                                {{ Form::select('investor', $investors, $site->investor_id, array('class' => 'form-control investor')) }}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info add-investor">{{ trans('main.add') }}</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.work_start_date') }} *</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {{ Form::text('start_date', $site->start_date, array('class' => 'form-control start-date')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.plan_end_date') }} *</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {{ Form::text('plan_end_date', $site->plan_end_date, array('class' => 'form-control plan-end-date')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.work_end_date') }}</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {{ Form::text('end_date', $site->end_date, array('class' => 'form-control end-date')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.project_manager') }} *</label>
                            {{ Form::select('project_manager', $employees, $site->project_manager_id, array('class' => 'form-control project-manager')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.status') }} *</label>
                            {{ Form::select('status', $statuses, $site->status_id, array('class' => 'form-control status')) }}
                        </div>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        <div class="text-center">
                            <a href="{{ route('GetSites') }}" class="btn btn-white m-t-n-xs"><strong>{{ trans('main.cancel') }}</strong></a>
                            <button type="button" class="btn btn-primary m-t-n-xs update-site"><strong>{{ trans('main.save') }}</strong></button>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    @include('modals.investor')
    @include('modals.map')

    <script>

        var investor_insert = '{{ trans('main.investor_insert') }}';
        var validation_error = '{{ trans('errors.validation_error') }}';
        var error = '{{ trans('errors.error') }}';

    </script>

    {{ HTML::script('js/functions/sites.js?v='.date('YmdHi')) }}
@endsection