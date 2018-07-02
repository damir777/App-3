@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ $page_title }}</h2>
        </div>
    </div>
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-lg-12">
            {{ Form::open(array('route' => ['ResourcesOverview', $type], 'class' => 'form-inline', 'method' => 'get', 'autocomplete' => 'off')) }}
                {{ Form::hidden('type', $type, array('class' => 'type')) }}
                <div class="form-group">
                    {{ Form::text('search_string', $search_string, array('class' => 'form-control search_string',
                        'placeholder' => trans('main.search_placeholder'))) }}
                </div>
                <div class="form-group">
                    {{ Form::select('search_filter', $resource_types, $search_filter, array('class' => 'form-control search_filter')) }}
                </div>
                <button class="btn btn-white">{{ trans('main.search') }}</button>
                <button type="button" class="btn btn-white make-pdf">PDF</button>
            {{ Form::close() }}
        </div>
    </div>

    @if (count($resources) > 0)
        <div class="row border-bottom white-bg dashboard-header">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <table class="footable table table-stripped toggle-arrow-tiny default breakpoint footable-loaded" data-page-size="5000">
                        <thead>
                        <tr>
                            @foreach ($table_header as $header)
                                <th class="footable-visible">{{ $header }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($resources as $resource)
                            <tr style="display: table-row;">
                                <td class="footable-visible">{{ $resource['code'] }}</td>
                                @if ($type != 5)
                                    <td class="footable-visible">{{ $resource['manufacturer'] }}</td>
                                    <td class="footable-visible">
                                        <a href="{{ route($resource['route'], $resource['id']) }}">{{ $resource['name'] }}</a>
                                    </td>
                                    <td class="footable-visible">{{ $resource['model'] }}</td>
                                @else
                                    <td class="footable-visible">
                                        <a href="{{ route($resource['route'], $resource['id']) }}">{{ $resource['name'] }}</a>
                                    </td>
                                    <td class="footable-visible">{{ $resource['work_type'] }}</td>
                                    <td class="footable-visible">{{ $resource['oib'] }}</td>
                                @endif
                                <td class="footable-visible">{{ $resource['current_site'] }}</td>
                                @if ($type != 5)
                                    <td class="footable-visible">{{ $resource['current_parking'] }}</td>
                                @else
                                    <td class="footable-visible">{{ $resource['current_status'] }}</td>
                                @endif
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{ HTML::script('js/functions/statistic.js?v='.date('YmdHi')) }}
@endsection