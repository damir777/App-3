@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.view_dwa') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        @if ($head_of_site_confirmation == 'T' || $manager_confirmation == 'T' || $can_confirm == 'T')
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox ">
                        <div class="ibox-content">
                            @if ($head_of_site_confirmation == 'T')
                                <h3 style="color: #1c84c6">Voditelj gradilišta je potvrdio ovaj radni list.</h3>
                            @endif
                            @if ($manager_confirmation == 'T')
                                <h3 style="color: #1c84c6">Poslovođa je potvrdio ovaj radni list.</h3>
                            @endif
                            @if ($can_confirm == 'T')
                                <a href="{{ route('ConfirmDWA', $dwa->id) }}" class="btn btn-primary">{{ trans('main.confirm') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="ibox ">
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-6">
                                <p><strong>{{ trans('main.site') }}:</strong> {{ $dwa->site_name }}</p>
                                <p><strong>{{ trans('main.machine') }}:</strong> {{ $dwa->machine_name }}</p>
                                <p><strong>{{ trans('main.date') }}:</strong> {{ $dwa->activity_date }}</p>
                            </div>
                            <div class="col-sm-6">
                                <p><strong>Stroj pregledan, podmazan i očišćen:</strong> {{ $dwa->machine_checked }}</p>
                                <p><strong>Oštećenje stroja:</strong> {{ $dwa->damage }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>{{ trans('main.activities') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>{{ trans('main.employee_name') }}</th>
                                <th class="text-center">{{ trans('main.start') }}</th>
                                <th class="text-center">{{ trans('main.end') }}</th>
                                <th>{{ trans('main.tool') }} / {{ trans('main.activity') }}</th>
                                <th class="text-center">{{ trans('main.hours') }}</th>
                                <th class="text-center">{{ trans('main.start_working_hours') }}</th>
                                <th class="text-center">{{ trans('main.machine_end_working_hours') }}</th>
                                <th class="text-center">{{ trans('main.working_hours') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($activities as $activity)
                                <tr>
                                    <td>{{ $activity['employee'] }}</td><td class="text-center">{{ $activity['start_hour'] }}</td>
                                    <td class="text-center">{{ $activity['end_hour'] }}</td>
                                    <td>{{ $activity['activity'].' '.$activity['tool'] }}</td>
                                    <td class="text-center">{{ $activity['hours'] }}</td>
                                    <td class="text-center">{{ $activity['start_working_hours'] }}</td>
                                    <td class="text-center">{{ $activity['end_working_hours'] }}</td>
                                    <td class="text-center">{{ $activity['working_hours'] }}</td>
                                </tr>
                            @endforeach
                            <tr class="hours-sum">
                                <td></td><td></td><td></td><td></td><td class="text-center">{{ $hours_sum }}</td><td></td><td></td>
                                <td class="text-center">{{ $working_hours_sum }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>{{ trans('main.fuel') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>{{ trans('main.employee_name') }}</th>
                                <th class="text-center">{{ trans('main.quantity') }}</th>
                                <th>{{ trans('main.invoice_number') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($fuel as $fuel_data)
                                <tr>
                                    <td>{{ $fuel_data['employee'] }}</td><td class="text-center">{{ $fuel_data['quantity'] }}</td>
                                    <td>{{ $fuel_data['invoice_number'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>{{ trans('main.fluids_change') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>{{ trans('main.employee_name') }}</th>
                                <th>{{ trans('main.component') }}</th>
                                <th class="text-center">{{ trans('main.quantity') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($fluids as $fluid)
                                <tr>
                                    <td>{{ $fluid['employee'] }}</td><td>{{ $fluid['component'] }}</td>
                                    <td class="text-center">{{ $fluid['quantity'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>{{ trans('main.filters_change') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>{{ trans('main.employee_name') }}</th>
                                <th>{{ trans('main.component') }}</th>
                                <th class="text-center">{{ trans('main.quantity') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($filters as $filter)
                                <tr>
                                    <td>{{ $filter['employee'] }}</td><td>{{ $filter['component'] }}</td>
                                    <td class="text-center">{{ $filter['quantity'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>{{ trans('main.notes') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>{{ trans('main.employee_name') }}</th>
                                <th>{{ trans('main.note') }}</th>
                                <th>{{ trans('main.photo') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($notes as $note)
                                <tr>
                                    <td>{{ $note['employee'] }}</td><td>{{ $note['note'] }}</td>
                                    @if($note['photo'])
                                        <td>
                                            <div class="lightBoxGallery">
                                                <a href="{{ $note['photo'] }}" data-gallery="">
                                                    {{ HTML::image($note['photo'], '', array('style' => 'height: 100px')) }}
                                                </a>
                                            </div>
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
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
        </div>
    </div>
@endsection