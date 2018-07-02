@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.dwa') }}
                @if ($show_entry_dwa == 'T')
                    <a href="{{ route('DWAEntry') }}" class="btn btn-w-m btn-primary pull-right">{{ trans('main.new_entry') }}</a>
                @endif
            </h2>
        </div>
    </div>

    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-lg-12">
            {{ Form::open(array('route' => 'GetDWA', 'class' => 'form-inline', 'method' => 'get', 'autocomplete' => 'off')) }}
                <div class="form-group" <?php if ($work_type < 4) echo 'style="display:none"'; ?>>
                    {{ Form::select('site', $sites, $site, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::select('machine', $machines, $machine, array('class' => 'form-control')) }}
                </div>
                <button class="btn btn-white">{{ trans('main.search') }}</button>
            {{ Form::close() }}
        </div>
    </div>

    @if (!$activities->isEmpty())
        <div class="row border-bottom white-bg dashboard-header">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <table class="footable table table-stripped toggle-arrow-tiny default breakpoint footable-loaded" data-page-size="30">
                        <thead>
                        <tr>
                            <th class="footable-visible">{{ trans('main.date') }}</th>
                            <th class="footable-visible">{{ trans('main.site') }}</th>
                            <th class="footable-visible">{{ trans('main.machine') }}</th>
                            <th class="footable-visible text-center">{{ trans('main.view') }}</th>
                            @if ($can_edit_dwa == 'T')
                                <th class="footable-visible text-center">{{ trans('main.edit') }}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($activities as $activity)
                            <tr style="display: table-row;">
                                <td class="footable-visible">{{ $activity->date }}</td>
                                <td class="footable-visible">{{ $activity->site->name }}</td>
                                <td class="footable-visible">{{ $activity->machine->name }}</td>
                                <td class="footable-visible text-center">
                                    <a href="{{ route('ViewDWA', $activity->id) }}"><i class="fa fa-edit"></i></a>
                                </td>
                                @if ($can_edit_dwa == 'T')
                                    @if ($activity->show_edit == 'T')
                                        <td class="footable-visible text-center">
                                            <a href="{{ route('EditDWA', $activity->id) }}"><i class="fa fa-edit"></i></a>
                                        </td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            @if ($can_edit_dwa == 'T')
                                <td colspan="5" class="footable-visible text-center">
                                    @if ($site || $machine)
                                        {{ $activities->appends(['site' => $site, 'machine' => $machine]) }}
                                    @else
                                        {{ $activities->links() }}
                                    @endif
                                </td>
                            @else
                                <td colspan="4" class="footable-visible text-center">
                                    @if ($site || $machine)
                                        {{ $activities->appends(['site' => $site, 'machine' => $machine]) }}
                                    @else
                                        {{ $activities->links() }}
                                    @endif
                                </td>
                            @endif
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection