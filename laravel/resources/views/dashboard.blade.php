@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.dashboard') }}</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div id="dashboard-loader-div" class="sk-loading">
                <div class="sk-spinner sk-spinner-double-bounce">
                    <div class="sk-double-bounce1"></div>
                    <div class="sk-double-bounce2"></div>
                </div>
                {{ Form::hidden('is_modal', 'F', array('id' => 'is-modal')) }}
                <div class="dashboard-wrapper">
                    <div class="construction-sites-wrapper">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h3>{{ trans('main.sites') }}</h3>
                            </div>
                            <div class="ibox-content">
                                <ul class="tag-list" style="padding: 0">
                                    <li><a href="" class="sites-list-item active" data-site-type="site">{{ trans('main.sites') }}</a></li>
                                    <li><a href="" class="sites-list-item" data-site-type="parking">{{ trans('main.parking') }}</a></li>
                                    <li><a href="" class="sites-map-item">{{ trans('main.map') }}</a></li>
                                </ul>
                                <div class="clearfix"></div>
                                <div class="row animated fadeInUp" id="sites-list"></div>
                                <div class="row" id="sites-map" style="display: none">
                                    <div id="gMap" style="width: 100%; height: 450px"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dashboard-wrapper">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h3>{{ trans('main.employees') }}</h3>
                        </div>
                        <div class="ibox-content">
                            {{ Form::hidden('employees_employee_type', 1, array('id' => 'employees-employee-type')) }}
                            {{ Form::hidden('employees_current_page', 1, array('id' => 'employees-current-page')) }}
                            <ul class="tag-list" style="padding: 0">
                                <li><a href="" class="employees-item active" data-employee-type="1">Godišnji odmor</a></li>
                                <li><a href="" class="employees-item" data-employee-type="2">Bolovanje</a></li>
                                <li><a href="" class="employees-item" data-employee-type="3">Slobodni dani</a></li>
                            </ul>
                            <div class="clearfix"></div>
                            <form role="form" class="form-inline search-form" id="employees-search-form">
                                <div class="form-group">
                                    <input type="text" placeholder="{{ trans('main.search_placeholder') }}" id="employees-list-search" class="form-control" autocomplete="off">
                                </div>
                                <button class="btn btn-info search-btn" id="employees-search-button" type="button">{{ trans('main.search') }}</button>
                            </form>
                            <div id="employees-list-data" style="display: none" class="animated fadeInUp">
                                <div class="dataTables_paginate paging_simple_numbers" id="employees-list-pagination">
                                    <ul class="pagination">
                                        <li class="paginate_button previous">
                                            <a href="#" class="employees-previous-pagination">{{ trans('main.previous') }}</a>
                                        </li>
                                        <li class="paginate_button next">
                                            <a href="#" class="employees-next-pagination">{{ trans('main.next') }}</a>
                                        </li>
                                    </ul>
                                </div>
                                <table class="footable table table-striped toggle-arrow-tiny default breakpoint footable-loaded dashboard-table" data-page-size="30">
                                    <thead>
                                    <tr id="employees-table-header"></tr>
                                    </thead>
                                    <tbody id="employees-table-body"></tbody>
                                </table>
                            </div>
                            <div class="animated fadeInUp no-data" id="employees-no-data"><h3>{{ trans('main.no_data') }}</h3></div>
                        </div>
                    </div>
                </div>
                <div class="dashboard-wrapper">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h3>{{ trans('main.resources') }}</h3>
                        </div>
                        <div class="ibox-content">
                            {{ Form::hidden('active_resource_type', 1, array('id' => 'active-resource-type')) }}
                            {{ Form::hidden('active_current_page', 1, array('id' => 'active-current-page')) }}
                            <ul class="tag-list" style="padding: 0">
                                <li><a href="" class="resources-item active" data-resource-type="1">{{ trans('main.machines') }}</a></li>
                                <li><a href="" class="resources-item" data-resource-type="2">{{ trans('main.tools') }}</a></li>
                                <li><a href="" class="resources-item" data-resource-type="3">{{ trans('main.equipment') }}</a></li>
                                <li><a href="" class="resources-item" data-resource-type="4">{{ trans('main.vehicles') }}</a></li>
                                <li><a href="" class="resources-item" data-resource-type="5">{{ trans('main.employees') }}</a></li>
                            </ul>
                            <div class="clearfix"></div>
                            <form role="form" class="form-inline search-form" id="active-search-form">
                                <div class="form-group">
                                    <input type="text" placeholder="{{ trans('main.search_placeholder') }}" id="active-list-search" class="form-control" autocomplete="off">
                                </div>
                                <button class="btn btn-info search-btn" id="active-search-button" type="button">{{ trans('main.search') }}</button>
                            </form>
                            <div id="active-list-data" style="display: none" class="animated fadeInUp">
                                <div class="dataTables_paginate paging_simple_numbers" id="active-list-pagination">
                                    <ul class="pagination">
                                        <li class="paginate_button previous">
                                            <a href="#" class="active-previous-pagination">{{ trans('main.previous') }}</a>
                                        </li>
                                        <li class="paginate_button next">
                                            <a href="#" class="active-next-pagination">{{ trans('main.next') }}</a>
                                        </li>
                                    </ul>
                                </div>
                                <table class="footable table table-striped toggle-arrow-tiny default breakpoint footable-loaded dashboard-table" data-page-size="30">
                                    <thead>
                                    <tr id="active-table-header"></tr>
                                    </thead>
                                    <tbody id="active-table-body"></tbody>
                                </table>
                            </div>
                            <div class="animated fadeInUp no-data" id="active-no-data"><h3>{{ trans('main.no_data') }}</h3></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.siteResources')

    <script>

        var site_trans = '{{ trans('main.site') }}';
        var parking_trans = '{{ trans('main.parking_single') }}';
        var status_trans = '{{ trans('main.status') }}';
        var machines_trans = '{{ trans('main.machines') }}';
        var tools_trans = '{{ trans('main.tools') }}';
        var equipment_trans = '{{ trans('main.equipment') }}';
        var vehicles_trans = '{{ trans('main.vehicles') }}';
        var employees_trans = '{{ trans('main.employees') }}';
        var error = '{{ trans('errors.error') }}';

    </script>

    {{ HTML::script('http://maps.googleapis.com/maps/api/js?key=AIzaSyDPatFLhUHzUaKaGiviHNXYGuudwpKg-EY') }}
    {{ HTML::script('js/functions/manipulations.js?v='.date('YmdHi')) }}
@endsection