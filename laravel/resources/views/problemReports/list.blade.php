@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.problem_reports') }}</h2>
        </div>
    </div>

    @if (!$reports->isEmpty())
        <div class="row border-bottom white-bg dashboard-header">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <table class="footable table table-stripped toggle-arrow-tiny default breakpoint footable-loaded problem-reports-table"
                        data-page-size="30">
                        <thead>
                        <tr>
                            <th class="footable-visible">{{ trans('main.employee') }}</th>
                            <th class="footable-visible">{{ trans('main.time') }}</th>
                            <th class="footable-visible">{{ trans('main.description') }}</th>
                            <th class="footable-visible">{{ trans('main.photo') }}</th>
                            <th class="footable-visible">{{ trans('main.seen_by') }}</th>
                            <th class="footable-visible">{{ trans('main.seen_time') }}</th>
                            <th class="footable-visible">{{ trans('main.view') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($reports as $report)
                            <tr style="display: table-row;">
                                <td class="footable-visible">{{ $report->employee->name }}</td>
                                <td class="footable-visible">{{ $report->time }}</td>
                                <td class="footable-visible">{{ $report->description }}</td>
                                <td class="footable-visible">
                                    <a href="{{ $report->photo }}" data-gallery="">
                                        {{ HTML::image($report->photo, '', array('style' => 'height: 50px')) }}
                                    </a>
                                </td>
                                <td class="footable-visible">{{ $report->seen_employee }}</td>
                                <td class="footable-visible">{{ $report->seen_time }}</td>
                                <td class="footable-visible">
                                    @if ($show_report_seen == 'T' && !$report->seen_employee)
                                        <button type="button" class="btn btn-info btn-xs seen-report"
                                            data-report-id="{{ $report->id }}">{{ trans('main.mark_as_seen') }}
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="7" class="footable-visible text-center">
                                {{ $reports->links() }}
                            </td>
                        </tr>
                        </tfoot>
                    </table>
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
    @endif

    <script>

        var error = '{{ trans('errors.error') }}';

    </script>

    {{ HTML::script('js/functions/problemReports.js?v='.date('YmdHi')) }}
@endsection