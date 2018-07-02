@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.tools') }}
                <a href="{{ route('AddTool') }}" class="btn btn-w-m btn-primary pull-right">{{ trans('main.add_tool') }}</a>
            </h2>
        </div>
    </div>

    @if (!$tools->isEmpty())
        <div class="row border-bottom white-bg dashboard-header">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <table class="footable table table-stripped toggle-arrow-tiny default breakpoint footable-loaded" data-page-size="30">
                        <thead>
                        <tr>
                            <th class="footable-visible">{{ trans('main.manufacturer') }}</th>
                            <th class="footable-visible">{{ trans('main.name') }}</th>
                            <th class="footable-visible">{{ trans('main.model') }}</th>
                            <th class="footable-visible">{{ trans('main.serial_number') }}</th>
                            <th class="footable-visible">{{ trans('main.status') }}</th>
                            <th class="footable-visible text-center">{{ trans('main.edit') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($tools as $tool)
                            <tr style="display: table-row;">
                                <td class="footable-visible">{{ $tool->manufacturer->name }}</td>
                                <td class="footable-visible">{{ $tool->name }}</td>
                                <td class="footable-visible">{{ $tool->model }}</td>
                                <td class="footable-visible">{{ $tool->serial_number }}</td>
                                <td class="footable-visible">{{ $tool->status->name }}</td>
                                <td class="footable-visible text-center">
                                    <a href="{{ route('EditTool', $tool->id) }}"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6" class="footable-visible text-center">
                                {{ $tools->links() }}
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection