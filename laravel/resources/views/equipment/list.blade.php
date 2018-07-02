@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.equipment') }}
                <a href="{{ route('AddEquipment') }}" class="btn btn-w-m btn-primary pull-right">{{ trans('main.add_equipment') }}</a>
            </h2>
        </div>
    </div>

    @if (!$equipment->isEmpty())
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

                        @foreach ($equipment as $equipment_row)
                            <tr style="display: table-row;">
                                <td class="footable-visible">{{ $equipment_row->manufacturer->name }}</td>
                                <td class="footable-visible">{{ $equipment_row->name }}</td>
                                <td class="footable-visible">{{ $equipment_row->model }}</td>
                                <td class="footable-visible">{{ $equipment_row->serial_number }}</td>
                                <td class="footable-visible">{{ $equipment_row->status->name }}</td>
                                <td class="footable-visible text-center">
                                    <a href="{{ route('EditEquipment', $equipment_row->id) }}"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6" class="footable-visible text-center">
                                {{ $equipment->links() }}
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection