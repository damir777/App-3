@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.new_entry') }}</h2>
        </div>
    </div>
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                {{ Form::open(array('url' => '#', 'autocomplete' => 'off', 'class' => 'machine-form')) }}
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div id="sites-div" style="display: none">
                                <div class="form-group">
                                    <label>{{ trans('main.site') }}</label>
                                    {{ Form::select('site', array(), null, array('class' => 'form-control site')) }}
                                </div>
                            </div>
                            <div id="employee-div" style="display: none">
                                <div class="form-group">
                                    <label>{{ trans('main.employee') }}</label>
                                    {{ Form::select('employee', array(), null, array('class' => 'form-control employee')) }}
                                </div>
                            </div>
                            <div id="machine-div" style="display: none">
                                <div class="form-group">
                                    <label>{{ trans('main.machine') }}</label>
                                    {{ Form::select('machine', array(), null, array('class' => 'form-control machine')) }}
                                </div>
                            </div>
                            <div id="initial-machine-check-div" style="margin-bottom: 10px; display: none">
                                <div>
                                    <label class="">
                                        <div class="icheckbox_square-green" style="position: relative;">
                                            <input type="checkbox" name="machine-checked" class="i-checks machine-checked" style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper" style="position: absolute; top: 0; left: 0; display: block; width: 100%; height: 100%; margin: 0; padding: 0; background: rgb(255, 255, 255); border: 0; opacity: 0;"></ins>
                                        </div>
                                        Stroj pregledan, podmazan i očišćen
                                    </label>
                                </div>
                                <div>
                                    <label class="">
                                        <div class="icheckbox_square-green" style="position: relative;">
                                            <input type="checkbox" name="damage" class="i-checks damage" style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper" style="position: absolute; top: 0; left: 0; display: block; width: 100%; height: 100%; margin: 0; padding: 0; background: rgb(255, 255, 255); border: 0; opacity: 0;"></ins>
                                        </div>
                                        Oštećenje stroja
                                    </label>
                                </div>
                                <div class="m-t m-b-md" id="initial-damage-note-div" style="display: none">
                                    <label>{{ trans('main.note') }}</label>
                                    <textarea class="form-control damage-note"></textarea>
                                </div>
                                <div style="margin-top: 10px">
                                    <button type="button" class="btn btn-primary btn-sm create-dwa">{{ trans('main.create_dwa') }}</button>
                                </div>
                            </div>
                            <div class="panel-group" id="accordion">
                                <div id="activities-div" class="panel panel-default" style="display: none">
                                    <div class="panel-heading">
                                        <h5 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="get-activities">{{ trans('main.tool') }} / {{ trans('main.activity') }}</a>
                                        </h5>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('main.start') }}</label>
                                                                <div class="input-group clockpicker" data-autoclose="true">
                                                                    <input type="text" class="form-control start-time">
                                                                    <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('main.end') }}</label>
                                                                <div class="input-group clockpicker" data-autoclose="true">
                                                                    <input type="text" class="form-control end-time">
                                                                    <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ trans('main.tool') }}</label>
                                                        {{ Form::select('tool', array(), null, array('class' => 'form-control tool')) }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ trans('main.activity') }}</label>
                                                        {{ Form::select('activity', array(), null, array('class' => 'form-control activity')) }}
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('main.start_working_hours') }}</label>
                                                                {{ Form::text('start_working_hours', 0, array('class' => 'form-control start-working-hours')) }}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('main.machine_end_working_hours') }}</label>
                                                                {{ Form::text('end_working_hours', 0, array('class' => 'form-control end-working-hours')) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-primary btn-sm save-activity">{{ trans('main.save') }}</button>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div id="activities-history" style="display: none">
                                                        <div class="feed-activity-list"></div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <h5 class="pull-right m-t">{{ trans('main.sum') }}: <span id="hours-sum"></span></h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="fuel-div" class="panel panel-default" style="display: none">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="get-fuel" aria-expanded="false">{{ trans('main.fuel') }}</a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('main.quantity') }}</label>
                                                                {{ Form::text('quantity', null, array('class' => 'form-control fuel-quantity')) }}
                                                            </div>
                                                            <button type="button" class="btn btn-primary btn-sm save-fuel">{{ trans('main.save') }}</button>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('main.invoice_number') }}</label>
                                                                {{ Form::text('invoice_number', null, array('class' => 'form-control invoice-number')) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div id="fuel-history" style="display: none">
                                                        <div class="feed-activity-list"></div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <h5 class="pull-right m-t">{{ trans('main.sum') }}: <span id="fuel-sum"></span></h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="fluids-div" class="panel panel-default" style="display: none">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="get-fluids" aria-expanded="false">{{ trans('main.fluids_change') }}</a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('main.component') }}</label>
                                                                {{ Form::select('fluid_component', array(), null, array('class' => 'form-control fluid-component')) }}
                                                            </div>
                                                            <button type="button" class="btn btn-primary btn-sm save-fluid">{{ trans('main.save') }}</button>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('main.quantity') }}</label>
                                                                {{ Form::text('quantity', null, array('class' => 'form-control fluid-quantity')) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div id="fluids-history" style="display: none">
                                                        <div class="feed-activity-list"></div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <h5 class="pull-right m-t">{{ trans('main.sum') }}: <span id="fluids-sum"></span></h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="filters" class="panel panel-default" style="display: none">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour" class="get-filters" aria-expanded="false">{{ trans('main.filters_change') }}</a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="panel-collapse collapse" aria-expanded="false">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('main.component') }}</label>
                                                                {{ Form::select('filter_component', array(), null, array('class' => 'form-control filter-component')) }}
                                                            </div>
                                                            <button type="button" class="btn btn-primary btn-sm save-filter">{{ trans('main.save') }}</button>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('main.quantity') }}</label>
                                                                {{ Form::text('quantity', null, array('class' => 'form-control filter-quantity')) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div id="filters-history" style="display: none">
                                                        <div class="feed-activity-list"></div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <h5 class="pull-right m-t">{{ trans('main.sum') }}: <span id="filters-sum"></span></h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="notes-div" class="panel panel-default" style="display: none">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" class="get-notes">{{ trans('main.notes') }}</a>
                                        </h4>
                                    </div>
                                    <div id="collapseFive" class="panel-collapse collapse" aria-expanded="false">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('main.note') }}</label>
                                                        {{ Form::textarea('note', null, array('class' => 'form-control note', 'rows' => 3)) }}
                                                    </div>
                                                    <button type="button" class="btn btn-primary btn-sm save-note">{{ trans('main.save') }}</button>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div id="notes-history" style="display: none">
                                                        <div class="feed-activity-list notes-list"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
                <div id="blueimp-gallery" class="blueimp-gallery">
                    <div class="slides"></div>
                    <h3 class="title"></h3>
                    <a class="prev">‹</a>
                    <a class="next">›</a>
                    <a class="close">×</a>
                    <a class="play-pause"></a>
                    <ol class="indicator"></ol>
                </div>
            </div>
        </div>
    </div>

    <script>

        var edit_dwa_id = null;
        var edit_machine_id = null;
        var start_working_hours_trans = '{{ trans('main.start_working_hours') }}';
        var end_working_hours_trans = '{{ trans('main.machine_end_working_hours') }}';
        var working_hours_error = '{{ trans('errors.end_working_hours') }}';
        var validation_error = '{{ trans('errors.validation_error') }}';
        var error = '{{ trans('errors.error') }}';

    </script>

    {{ HTML::script('js/functions/dwa.js?v='.date('YmdHi')) }}
@endsection