@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.machines') }}
                <a href="{{ route('AddMachine') }}" class="btn btn-w-m btn-primary pull-right">{{ trans('main.add_machine') }}</a>
            </h2>
        </div>
    </div>

    @if (!$machines->isEmpty())
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

                        @foreach ($machines as $machine)
                            <tr style="display: table-row;">
                                <td class="footable-visible">{{ $machine->manufacturer->name }}</td>
                                <td class="footable-visible">{{ $machine->name }}</td>
                                <td class="footable-visible">{{ $machine->model }}</td>
                                <td class="footable-visible">{{ $machine->serial_number }}</td>
                                <td class="footable-visible">{{ $machine->status->name }}</td>
                                <td class="footable-visible text-center">
                                    <a href="{{ route('EditMachine', $machine->id) }}"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6" class="footable-visible text-center">
                                {{ $machines->links() }}
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection