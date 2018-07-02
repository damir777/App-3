@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.new_parking') }}</h2>
        </div>
    </div>
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                {{ Form::open(array('url' => '#', 'autocomplete' => 'off', 'class' => 'parking-form')) }}
                {{ Form::hidden('latitude', 0, array('id' => 'latitude')) }}
                {{ Form::hidden('longitude', 0, array('id' => 'longitude')) }}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ trans('main.name') }} *</label>
                            <div class="input-group">
                                {{ Form::text('name', null, array('class' => 'form-control name')) }}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#locationModal">
                                        <i class="fa fa-map-marker fa-lg" aria-hidden="true"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.address') }} *</label>
                            {{ Form::text('address', null, array('class' => 'form-control address')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.status') }} *</label>
                            {{ Form::select('status', $statuses, null, array('class' => 'form-control status')) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('main.notes') }}</label>
                            {{ Form::textarea('notes', null, array('class' => 'form-control notes')) }}
                        </div>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-6">
                        <div class="text-center">
                            <a href="{{ route('GetParking') }}" class="btn btn-white m-t-n-xs"><strong>{{ trans('main.cancel') }}</strong></a>
                            <button type="button" class="btn btn-primary m-t-n-xs insert-parking"><strong>{{ trans('main.save') }}</strong></button>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    @include('modals.map')

    <script>

        var validation_error = '{{ trans('errors.validation_error') }}';
        var error = '{{ trans('errors.error') }}';

    </script>

    {{ HTML::script('js/functions/parking.js?v='.date('YmdHi')) }}
@endsection